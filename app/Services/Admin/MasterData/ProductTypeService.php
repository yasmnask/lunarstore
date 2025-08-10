<?php

namespace App\Services\Admin\MasterData;

use App\Models\ProductType;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductTypeService
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
            2 => 'app_name',
            3 => 'type_name',
            4 => 'unit',
            5 => 'description',
            6 => 'is_deleted',
            7 => 'created_at'
        ];
        $orderBy = $columns[$orderColumnIndex] ?? 'id';

        $query = ProductType::select('id', 'app_name', 'type_name', 'unit', 'description', 'is_deleted', 'created_at');

        $recordsTotal = ProductType::count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('app_name', 'like', "%{$search}%")
                    ->orWhere('type_name', 'like', "%{$search}%")
                    ->orWhere('unit', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        $types = $query->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $formattedData = $types->map(function ($type) {
            return [
                'id' => $type->id,
                'app_name' => $type->app_name,
                'type_name' => $type->type_name,
                'unit' => $type->unit ?? '-',
                'description' => $type->description ?? '-',
                'is_deleted' => $type->is_deleted,
                'created_at' => $type->created_at->format('d F Y H:i'),
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
     * Create a new product type.
     *
     * @param array $data
     * @return ProductType
     * @throws ValidationException
     */
    public function createProductType(array $data): ProductType
    {
        $typeData = [
            'app_name' => $data['app_name'],
            'type_name' => $data['type_name'],
            'unit' => $data['unit'] ?? null,
            'description' => $data['description'] ?? null,
            'is_deleted' => false,
            'created_at' => now(),
        ];

        return ProductType::create($typeData);
    }

    /**
     * Create multiple product types for a single app.
     *
     * @param string $appName
     * @param array $typesData
     * @return int
     * @throws ValidationException
     */
    public function createMultipleProductTypes(string $appName, array $typesData): int
    {
        $createdCount = 0;

        foreach ($typesData as $typeData) {
            $productTypeData = [
                'app_name' => $appName,
                'type_name' => $typeData['type_name'],
                'unit' => $typeData['unit'] ?? null,
                'description' => $typeData['description'] ?? null,
                'is_deleted' => false,
                'created_at' => now(),
            ];

            ProductType::create($productTypeData);
            $createdCount++;
        }

        return $createdCount;
    }

    /**
     * Update an existing product type.
     *
     * @param int $typeId
     * @param array $data
     * @return ProductType
     * @throws ValidationException
     */
    public function updateProductType(int $typeId, array $data): ProductType
    {
        $type = ProductType::findOrFail($typeId);

        $typeData = [
            'app_name' => $data['app_name'],
            'type_name' => $data['type_name'],
            'unit' => $data['unit'] ?? null,
            'description' => $data['description'] ?? null,
        ];

        $type->update($typeData);

        return $type;
    }

    /**
     * Update a specific field of a product type.
     *
     * @param int $typeId
     * @param string $field
     * @param mixed $value
     * @return ProductType
     */
    public function updateTypeField(int $typeId, string $field, $value): ProductType
    {
        $type = ProductType::findOrFail($typeId);

        $type->update([$field => $value]);

        return $type;
    }

    /**
     * Toggle product type deleted status (soft delete).
     *
     * @param int $typeId
     * @param bool $status
     * @return ProductType
     */
    public function toggleTypeStatus(int $typeId, bool $status): ProductType
    {
        $type = ProductType::findOrFail($typeId);

        $type->update(['is_deleted' => $status]);

        return $type;
    }
}
