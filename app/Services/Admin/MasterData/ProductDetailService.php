<?php

namespace App\Services\Admin\MasterData;

use App\Models\ProductDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductDetailService
{
    public function getDataTables(Request $request): array
    {
        $draw = $request->input('draw', 1);
        $start = $request->input('start', 0);
        $length = $request->input('length', 10);
        $search = $request->input('search.value', '');
        $orderColumnIndex = $request->input('order.0.column', 2);
        $orderDir = $request->input('order.0.dir', 'asc');

        $columns = [
            1 => 'id',
            2 => 'product_id',
            3 => 'product_type_id',
            4 => 'duration',
            5 => 'price',
            6 => 'notes',
            7 => 'is_deleted',
            8 => 'created_at'
        ];
        $orderBy = $columns[$orderColumnIndex] ?? 'id';

        $query = ProductDetail::with(['product', 'productType'])
            ->select('product_details.*');

        $recordsTotal = ProductDetail::count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('duration', 'like', "%{$search}%")
                    ->orWhere('price', 'like', "%{$search}%")
                    ->orWhere('notes', 'like', "%{$search}%")
                    ->orWhereHas('product', function ($productQuery) use ($search) {
                        $productQuery->where('app_name', 'like', "%{$search}%");
                    })
                    ->orWhereHas('productType', function ($typeQuery) use ($search) {
                        $typeQuery->where('type_name', 'like', "%{$search}%");
                    });
            });
        }

        $recordsFiltered = $query->count();

        // Order by product name first, then by type name (handling nulls), then by specified column
        $details = $query->join('products', 'product_details.product_id', '=', 'products.id')
            ->leftJoin('product_types', 'product_details.product_type_id', '=', 'product_types.id')
            ->orderBy('products.app_name', 'asc')
            ->orderByRaw('COALESCE(product_types.type_name, "ZZZ_NO_TYPE") asc') // Put null types at the end
            ->orderBy('product_details.' . $orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        // Format data for DataTables with grouping information
        $formattedData = [];
        $lastProduct = null;
        $lastProductType = null;

        foreach ($details as $detail) {
            $productName = $detail->product->app_name;
            $typeName = $detail->productType ? $detail->productType->type_name : 'Tanpa Tipe';

            $rowData = [
                'id' => $detail->id,
                'product_id' => $detail->product_id,
                'product_name' => $productName,
                'product_type_id' => $detail->product_type_id,
                'type_name' => $typeName,
                'duration' => $detail->duration ?? '-',
                'price' => $detail->price,
                'price_formatted' => $detail->formatted_price,
                'notes' => $detail->notes ?? '-',
                'is_deleted' => $detail->is_deleted ?? false,
                'created_at' => $detail->created_at->format('d F Y H:i'),
                'show_product' => ($lastProduct !== $productName),
                'show_type' => ($lastProduct !== $productName || $lastProductType !== $typeName)
            ];

            $formattedData[] = $rowData;

            $lastProduct = $productName;
            $lastProductType = $typeName;
        }

        return [
            'draw' => (int) $draw,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $formattedData,
        ];
    }

    /**
     * Create a new product detail.
     *
     * @param array $data
     * @return ProductDetail
     * @throws ValidationException
     */
    public function createProductDetail(array $data): ProductDetail
    {
        $detailData = [
            'product_id' => $data['product_id'],
            'product_type_id' => $data['product_type_id'] ?: null, // Handle empty string as null
            'duration' => $data['duration'] ?? null,
            'price' => $data['price'],
            'notes' => $data['notes'] ?? null,
            'is_deleted' => false,
            'created_at' => now(),
        ];

        return ProductDetail::create($detailData);
    }

    /**
     * Create multiple product details.
     *
     * @param int $productId
     * @param int|null $productTypeId
     * @param array $detailsData
     * @return int
     * @throws ValidationException
     */
    public function createMultipleProductDetails(int $productId, ?int $productTypeId, array $detailsData): int
    {
        $createdCount = 0;

        foreach ($detailsData as $detailData) {
            $productDetailData = [
                'product_id' => $productId,
                'product_type_id' => $productTypeId ?: null,
                'duration' => $detailData['duration'] ?? null,
                'price' => $detailData['price'],
                'notes' => $detailData['notes'] ?? null, // Individual notes for each detail
                'is_deleted' => false,
                'created_at' => now(),
            ];

            ProductDetail::create($productDetailData);
            $createdCount++;
        }

        return $createdCount;
    }

    /**
     * Update an existing product detail.
     *
     * @param int $detailId
     * @param array $data
     * @return ProductDetail
     * @throws ValidationException
     */
    public function updateProductDetail(int $detailId, array $data): ProductDetail
    {
        $detail = ProductDetail::findOrFail($detailId);

        $detailData = [
            'product_id' => $data['product_id'],
            'product_type_id' => $data['product_type_id'] ?: null, // Handle empty string as null
            'duration' => $data['duration'] ?? null,
            'price' => $data['price'],
            'notes' => $data['notes'] ?? null,
        ];

        $detail->update($detailData);

        return $detail;
    }

    /**
     * Update a specific field of a product detail.
     *
     * @param int $detailId
     * @param string $field
     * @param mixed $value
     * @return ProductDetail
     */
    public function updateDetailField(int $detailId, string $field, $value): ProductDetail
    {
        $detail = ProductDetail::findOrFail($detailId);

        $detail->update([$field => $value]);

        return $detail;
    }

    /**
     * Toggle product detail deleted status (soft delete).
     *
     * @param int $detailId
     * @param bool $status
     * @return ProductDetail
     */
    public function toggleDetailStatus(int $detailId, bool $status): ProductDetail
    {
        $detail = ProductDetail::findOrFail($detailId);

        $detail->update(['is_deleted' => $status]);

        return $detail;
    }
}
