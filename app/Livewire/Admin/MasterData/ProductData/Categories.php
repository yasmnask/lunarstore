<?php

namespace App\Livewire\Admin\MasterData\ProductData;

use App\Models\ProductCategory;
use App\Services\Admin\MasterData\ProductCategoryService;
use App\Services\CloudflareR2Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

#[Title('Data Master Kategori Produk')]
#[Layout('layouts.admin')]
class Categories extends Component
{
    use WithFileUploads;

    // Form properties
    public $categoryId = null;
    public $title = '';
    public $slug = '';
    public $description = '';
    public $image = '';
    public $imageFile = null;
    public $currentImageUrl = '';

    // UI State
    public $isEditing = false;
    public $isUploading = false;

    public function rules()
    {
        $rules = [
            'title' => [
                'required',
                'string',
                'max:75',
                Rule::unique('product_categories', 'title')->ignore($this->categoryId)
            ],
            'slug' => [
                'required',
                'string',
                'max:150',
                Rule::unique('product_categories', 'slug')->ignore($this->categoryId)
            ],
            'description' => 'nullable|string|max:150',
            'image' => 'nullable|string|max:255',
            'imageFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3120', // 3MB max
        ];

        return $rules;
    }

    public function mount()
    {
        $this->resetForm();
    }

    public function updatedTitle()
    {
        if (!$this->isEditing) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function updatedImageFile()
    {
        $this->validate([
            'imageFile' => 'image|mimes:jpeg,png,jpg,gif,webp|max:3120'
        ]);
    }

    public function openAddModal()
    {
        $this->dispatch('categories:show-add-modal');
        $this->resetForm();
    }

    public function openEditModal($id)
    {
        $category = ProductCategory::findOrFail($id);
        $this->categoryId = $category->id;
        $this->title = $category->title;
        $this->slug = $category->slug;
        $this->description = $category->description ?? '';
        $this->image = $category->image ?? '';
        $this->currentImageUrl = $category->image ?? '';
        $this->isEditing = true;
        $this->dispatch('categories:show-add-modal');
    }

    public function removeCurrentImage()
    {
        $this->image = '';
        $this->currentImageUrl = '';
    }

    public function save(ProductCategoryService $categoryService, CloudflareR2Service $r2Service)
    {
        try {
            $this->isUploading = true;
            $data = $this->validate();

            // Handle image upload
            if ($this->imageFile) {
                if (!$r2Service->validateImage($this->imageFile)) {
                    throw new \Exception('File gambar tidak valid atau terlalu besar (maksimal 3MB).');
                }

                $uploadedUrl = $r2Service->uploadFile($this->imageFile, 'products/categories');

                if (!$uploadedUrl) {
                    throw new \Exception('Gagal mengupload gambar ke cloud storage.');
                }

                // Delete old image if editing and has old image
                if ($this->isEditing && $this->currentImageUrl && $this->currentImageUrl !== $uploadedUrl) {
                    $r2Service->deleteFile($this->currentImageUrl);
                }

                $data['image'] = $uploadedUrl;
            } elseif (!$this->image && $this->currentImageUrl) {
                // Image was removed
                if ($this->isEditing) {
                    $r2Service->deleteFile($this->currentImageUrl);
                }
                $data['image'] = null;
            } else {
                // Keep existing image
                $data['image'] = $this->image ?: null;
            }

            if ($this->isEditing) {
                $categoryService->updateCategory($this->categoryId, $data);
                $message = 'Data kategori berhasil diperbarui.';
            } else {
                $categoryService->createCategory($data);
                $message = 'Data kategori berhasil dibuat.';
            }

            $this->resetForm();

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => $message,
                'timer' => 2500,
            ]);

            $this->dispatch('categories:hide-modal');
            $this->dispatch('categories:refresh-datatable');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => $this->isEditing ? 'Gagal memperbarui data kategori.' : 'Gagal membuat kategori baru.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        } finally {
            $this->isUploading = false;
        }
    }

    public function toggleCategoryStatus($categoryId, ProductCategoryService $categoryService)
    {
        try {
            $category = ProductCategory::findOrFail($categoryId);
            $newStatus = !$category->is_deleted;

            $categoryService->toggleCategoryStatus($categoryId, $newStatus);

            $statusText = $newStatus ? 'dihapus' : 'dipulihkan';

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => "Kategori berhasil {$statusText}.",
                'timer' => 2500,
            ]);

            $this->dispatch('categories:refresh-datatable');
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Gagal mengubah status kategori.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function updateField($categoryId, $field, $value, ProductCategoryService $categoryService)
    {
        try {
            // Validate the field update
            $allowedFields = ['title', 'slug', 'description'];

            if (!in_array($field, $allowedFields)) {
                throw new \Exception('Field tidak valid.');
            }

            $validationRules = [
                'title' => [
                    'required',
                    'string',
                    'max:75',
                    Rule::unique('product_categories', 'title')->ignore($categoryId)
                ],
                'slug' => [
                    'required',
                    'string',
                    'max:150',
                    Rule::unique('product_categories', 'slug')->ignore($categoryId)
                ],
                'description' => 'nullable|string|max:150',
            ];

            if (isset($validationRules[$field])) {
                $validator = validator([$field => $value], [$field => $validationRules[$field]]);

                if ($validator->fails()) {
                    throw new \Exception($validator->errors()->first($field));
                }
            }

            // Auto-generate slug if title is updated
            if ($field === 'title') {
                $categoryService->updateCategoryField($categoryId, 'title', $value);
                $categoryService->updateCategoryField($categoryId, 'slug', Str::slug($value));
            } else {
                $categoryService->updateCategoryField($categoryId, $field, $value);
            }

            $this->dispatch('categories:field-updated', [
                'success' => true,
                'message' => 'Data berhasil diperbarui.',
                'field' => $field,
                'refresh_slug' => $field === 'title'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('categories:field-updated', [
                'success' => false,
                'message' => $e->getMessage(),
                'field' => $field,
                'refresh_slug' => false
            ]);
        }
    }

    public function resetForm()
    {
        $this->categoryId = null;
        $this->title = '';
        $this->slug = '';
        $this->description = '';
        $this->image = '';
        $this->imageFile = null;
        $this->currentImageUrl = '';
        $this->resetValidation();

        $this->isEditing = false;
        $this->isUploading = false;
    }

    public function render()
    {
        return view('livewire.admin.master-data.product-data.categories');
    }
}
