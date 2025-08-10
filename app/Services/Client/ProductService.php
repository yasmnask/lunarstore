<?php

namespace App\Services\Client;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\ProductDetail;
use App\Models\ProductType;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;

class ProductService
{
    /**
     * Get all categories for filtering
     */
    public function getCategories(): \Illuminate\Database\Eloquent\Collection
    {
        return ProductCategory::where('is_deleted', false)
            ->orderBy('id', 'ASC')
            ->select(['id', 'slug', 'title'])
            ->get();
    }

    /**
     * Get category by slug
     */
    public function getCategoryBySlug(?string $slug): ?ProductCategory
    {
        if (empty($slug)) {
            return null;
        }

        return ProductCategory::where('slug', $slug)
            ->where('is_deleted', false)
            ->first();
    }

    /**
     * Get paginated products with optional category filter
     */
    public function getPaginatedProducts(?string $categorySlug = null, int $perPage = 8): LengthAwarePaginator
    {
        $query = Product::select([
            'products.*',
            DB::raw('(SELECT MIN(price) FROM product_details WHERE product_id = products.id AND is_deleted = false) AS starting_price')
        ])
            ->where('is_deleted', false);

        // Apply category filter if provided
        if (!empty($categorySlug)) {
            $category = $this->getCategoryBySlug($categorySlug);
            if ($category) {
                $query->where('category_id', $category->id);
            }
        }

        return $query->orderBy('app_name')
            ->paginate($perPage, ['*'], 'page');
    }

    /**
     * Get detailed product information for product details page
     */
    public function getProductDetails(int $productId): ?array
    {
        $query = "SELECT pd.id AS product_details_id, pd.duration, pd.price, pd.notes AS product_details_notes, 
                     p.id AS product_id, p.app_name, p.description AS product_description, 
                     p.cover_img, p.notes AS product_notes, p.ready_stock, p.is_topup,
                     pc.title AS category, pt.type_name AS product_type_name, 
                     pt.id AS product_type_id, pt.description AS product_type_description, pt.unit
              FROM products p
              JOIN product_categories pc ON p.category_id = pc.id
              LEFT JOIN product_details pd ON p.id = pd.product_id AND pd.is_deleted = false
              LEFT JOIN product_types pt ON pd.product_type_id = pt.id AND pt.is_deleted = false
              WHERE p.id = ? AND p.ready_stock = 1 AND p.is_deleted = false";

        $results = DB::select($query, [$productId]);

        if (empty($results)) {
            return null;
        }

        $firstRow = $results[0];
        $data = [
            'id' => $firstRow->product_id,
            'app_name' => $firstRow->app_name,
            'description' => $firstRow->product_description,
            'cover_img' => $firstRow->cover_img,
            'category' => $firstRow->category,
            'ready_stock' => $firstRow->ready_stock,
            'notes' => $firstRow->product_notes,
            'have_product_type' => false,
            'is_topup' => ($firstRow->is_topup ?? false) === 1,
            'plans' => [],
            'durations' => []
        ];

        // Check if product has types
        $hasTypes = collect($results)->whereNotNull('product_type_id')->count() > 0;

        if ($hasTypes) {
            $data['have_product_type'] = true;
            $data['plans'] = $this->organizePlansFromResults($results);
        } else {
            $data['have_product_type'] = false;
            $data['durations'] = $this->organizeDurationsFromResults($results);
        }

        return $data;
    }

    /**
     * Organize results into plans structure
     */
    private function organizePlansFromResults($results): array
    {
        $plans = [];
        $planMap = [];

        foreach ($results as $row) {
            if (!$row->product_type_id) continue;

            $typeId = $row->product_type_id;
            $typeName = $row->product_type_name;

            // Initialize plan if not exists
            if (!isset($planMap[$typeId])) {
                $planMap[$typeId] = count($plans);
                $plans[] = [
                    'id' => $typeId,
                    'name' => $typeName,
                    'description' => $row->product_type_description,
                    'durations' => []
                ];
            }

            $planIndex = $planMap[$typeId];

            // Check if duration already exists for this plan
            $existingDuration = collect($plans[$planIndex]['durations'])
                ->where('id', $row->product_details_id)
                ->first();

            if (!$existingDuration) {
                $dataPlan = [
                    'id' => $row->product_details_id,
                    'duration' => $row->duration ?? 'No Duration',
                    'price' => $row->price,
                    'notes' => $row->product_details_notes,
                ];
                // if is_topup true add key unit
                if ($row->is_topup) {
                    $dataPlan['unit'] = $row->unit ?? null;
                }
                $plans[$planIndex]['durations'][] = $dataPlan;
            }
        }

        return $plans;
    }

    /**
     * Organize results into durations structure (for products without types)
     */
    private function organizeDurationsFromResults($results): array
    {
        $durations = [];
        $addedIds = [];

        foreach ($results as $row) {
            if (!in_array($row->product_details_id, $addedIds)) {
                $durations[] = [
                    'id' => $row->product_details_id,
                    'duration' => $row->duration ?? 'No Duration',
                    'price' => $row->price,
                    'notes' => $row->product_details_notes
                ];
                $addedIds[] = $row->product_details_id;
            }
        }

        return $durations;
    }

    /**
     * Format price to Rupiah
     */
    public function formatPrice(?int $price): string
    {
        if ($price === null) {
            return '-';
        }
        return 'Rp ' . number_format($price, 0, ',', '.');
    }

    /**
     * Get category title for display
     */
    public function getCategoryTitle(?string $categorySlug): string
    {
        if (empty($categorySlug)) {
            return 'All Products';
        }

        $category = $this->getCategoryBySlug($categorySlug);
        return $category ? $category->title : 'All Products';
    }

    /**
     * Truncate description text
     */
    public function truncateDescription(string $description, int $limit = 150): string
    {
        return strlen($description) > $limit
            ? substr($description, 0, $limit) . '...'
            : $description;
    }

    /**
     * Add item to cart
     */
    public function addToCart(array $cartData): bool
    {
        // Session-based cart implementation
        $cart = session()->get('cart', []);
        $cartItemId = uniqid();

        $cart[$cartItemId] = [
            'product_id' => $cartData['product_id'],
            'product_detail_id' => $cartData['product_detail_id'],
            'plan_id' => $cartData['plan_id'] ?? null,
            'price' => $cartData['price'],
            'quantity' => $cartData['quantity'] ?? 1,
            'added_at' => now()
        ];

        session()->put('cart', $cart);

        return true;
    }

    /**
     * Get cart items count
     */
    public function getCartItemsCount(): int
    {
        $cart = session()->get('cart', []);
        return count($cart);
    }

    /**
     * Get cart total
     */
    public function getCartTotal(): int
    {
        $cart = session()->get('cart', []);
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}
