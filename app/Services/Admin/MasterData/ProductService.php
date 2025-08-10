<?php

namespace App\Services\Admin\MasterData;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductService
{
    public function getDataTables(Request $request): array
    {
        $draw = $request->input('draw', 1);
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');
        $orderColumnIndex = $request->input('order.0.column', 1);
        $orderDir = $request->input('order.0.dir', 'asc');

        $columns = [
            1 => 'id',
            2 => 'cover_img',
            3 => 'app_name',
            4 => 'category_id',
            5 => 'description',
            6 => 'notes',
            7 => 'is_topup',
            8 => 'ready_stock',
            9 => 'is_deleted',
            10 => 'created_at'
        ];
        $orderBy = $columns[$orderColumnIndex] ?? 'id';

        $query = Product::with('category')
            ->select('products.*')
            ->orderBy('app_name', 'asc');

        $recordsTotal = Product::count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('app_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('category', function ($categoryQuery) use ($search) {
                        $categoryQuery->where('title', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = $query->count();

        $products = $query->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $formattedData = $products->map(function ($product) {
            return [
                'id' => $product->id,
                'app_name' => $product->app_name,
                'description' => $product->description,
                'notes' => $product->notes ?? '-',
                'cover_img' => $product->cover_img,
                'category_id' => $product->category_id,
                'category_name' => $product->category->title ?? '-',
                'is_topup' => $product->is_topup,
                'ready_stock' => $product->ready_stock,
                'is_deleted' => $product->is_deleted,
                'created_at' => $product->created_at->format('d F Y H:i'),
            ];
        });

        return [
            'draw' => (int) $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $formattedData,
        ];
    }

    /**
     * Create a new product.
     *
     * @param array $data
     * @return Product
     * @throws ValidationException
     */
    public function createProduct(array $data): Product
    {
        $productData = [
            'app_name' => $data['app_name'],
            'description' => $data['description'],
            'notes' => $data['notes'] ?? null,
            'cover_img' => $data['cover_img'] ?? null,
            'is_topup' => $data['is_topup'] ?? false,
            'ready_stock' => $data['ready_stock'] ?? true,
            'category_id' => $data['category_id'],
            'is_deleted' => false,
            'created_at' => now(),
        ];

        return Product::create($productData);
    }

    /**
     * Update an existing product.
     *
     * @param int $productId
     * @param array $data
     * @return Product
     * @throws ValidationException
     */
    public function updateProduct(int $productId, array $data): Product
    {
        $product = Product::findOrFail($productId);

        $productData = [
            'app_name' => $data['app_name'],
            'description' => $data['description'],
            'notes' => $data['notes'] ?? null,
            'cover_img' => $data['cover_img'] ?? null,
            'is_topup' => $data['is_topup'] ?? false,
            'ready_stock' => $data['ready_stock'] ?? true,
            'category_id' => $data['category_id'],
        ];

        $product->update($productData);

        return $product;
    }

    /**
     * Update a specific field of a product.
     *
     * @param int $productId
     * @param string $field
     * @param mixed $value
     * @return Product
     */
    public function updateProductField(int $productId, string $field, $value): Product
    {
        $product = Product::findOrFail($productId);

        $product->update([$field => $value]);

        return $product;
    }

    /**
     * Toggle product deleted status (soft delete).
     *
     * @param int $productId
     * @param bool $status
     * @return Product
     */
    public function toggleProductStatus(int $productId, bool $status): Product
    {
        $product = Product::findOrFail($productId);

        $product->update(['is_deleted' => $status]);

        return $product;
    }
}
