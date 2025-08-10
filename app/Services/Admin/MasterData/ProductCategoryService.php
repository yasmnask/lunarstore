<?php

namespace App\Services\Admin\MasterData;

use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ProductCategoryService
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
            2 => 'title',
            3 => 'slug',
            4 => 'description',
            5 => 'image',
            6 => 'is_deleted',
            7 => 'created_at'
        ];
        $orderBy = $columns[$orderColumnIndex] ?? 'id';

        $query = ProductCategory::select('id', 'title', 'slug', 'description', 'image', 'is_deleted', 'created_at');

        $recordsTotal = ProductCategory::count();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $recordsFiltered = $query->count();

        $categories = $query->orderBy($orderBy, $orderDir)
            ->skip($start)
            ->take($length)
            ->get();

        $formattedData = $categories->map(function ($category) {
            return [
                'id' => $category->id,
                'title' => $category->title,
                'slug' => $category->slug,
                'description' => $category->description ?? '-',
                'image' => $category->image ?? '-',
                'is_deleted' => $category->is_deleted,
                'created_at' => $category->created_at->format('d F Y H:i'),
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
     * Create a new category.
     *
     * @param array $data
     * @return ProductCategory
     * @throws ValidationException
     */
    public function createCategory(array $data): ProductCategory
    {
        $categoryData = [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
            'is_deleted' => false,
            'created_at' => now(),
        ];

        return ProductCategory::create($categoryData);
    }

    /**
     * Update an existing category.
     *
     * @param int $categoryId
     * @param array $data
     * @return ProductCategory
     * @throws ValidationException
     */
    public function updateCategory(int $categoryId, array $data): ProductCategory
    {
        $category = ProductCategory::findOrFail($categoryId);

        $categoryData = [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'description' => $data['description'] ?? null,
            'image' => $data['image'] ?? null,
        ];

        $category->update($categoryData);

        return $category;
    }

    /**
     * Update a specific field of a category.
     *
     * @param int $categoryId
     * @param string $field
     * @param mixed $value
     * @return ProductCategory
     */
    public function updateCategoryField(int $categoryId, string $field, $value): ProductCategory
    {
        $category = ProductCategory::findOrFail($categoryId);

        $category->update([$field => $value]);

        return $category;
    }

    /**
     * Toggle category deleted status (soft delete).
     *
     * @param int $categoryId
     * @param bool $status
     * @return ProductCategory
     */
    public function toggleCategoryStatus(int $categoryId, bool $status): ProductCategory
    {
        $category = ProductCategory::findOrFail($categoryId);

        $category->update(['is_deleted' => $status]);

        return $category;
    }
}
