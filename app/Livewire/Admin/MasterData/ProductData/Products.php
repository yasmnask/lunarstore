<?php

namespace App\Livewire\Admin\MasterData\ProductData;

use App\Models\Product;
use App\Models\ProductCategory;
use App\Services\Admin\MasterData\ProductService;
use App\Services\CloudflareR2Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\ValidationException;

#[Title('Data Master Produk')]
#[Layout('layouts.admin')]
class Products extends Component
{
    use WithFileUploads;

    // Form properties
    public $productId = null;
    public $app_name = '';
    public $description = '';
    public $cover_img = '';
    public $notes = '';
    public $is_topup = false;
    public $ready_stock = true;
    public $category_id = '';
    public $imageFile = null;
    public $currentImageUrl = '';

    // UI State
    public $isEditing = false;
    public $isUploading = false;
    public $categories = [];

    public function rules()
    {
        $rules = [
            'app_name' => 'required|string|max:200',
            'description' => 'required|string',
            'notes' => 'nullable|string|max:150',
            'category_id' => 'required|exists:product_categories,id',
            'is_topup' => 'boolean',
            'ready_stock' => 'boolean',
            'cover_img' => 'nullable|string|max:255',
            'imageFile' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120', // 5MB max
        ];

        return $rules;
    }

    public function mount()
    {
        $this->loadCategories();
        $this->resetForm();
    }

    public function loadCategories()
    {
        $this->categories = ProductCategory::where('is_deleted', false)
            ->orderBy('title')
            ->get(['id', 'title']);
    }

    public function updatedImageFile()
    {
        $this->validate([
            'imageFile' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120'
        ]);
    }

    public function openAddModal()
    {
        $this->dispatch('products:show-add-modal');
        $this->resetForm();
    }

    public function openEditModal($id)
    {
        $product = Product::with('category')->findOrFail($id);
        $this->productId = $product->id;
        $this->app_name = $product->app_name;
        $this->description = $product->description;
        $this->notes = $product->notes ?? '';
        $this->is_topup = $product->is_topup;
        $this->ready_stock = $product->ready_stock;
        $this->category_id = $product->category_id;
        $this->cover_img = $product->cover_img ?? '';
        $this->currentImageUrl = $product->cover_img ?? '';
        $this->isEditing = true;
        $this->dispatch('products:show-add-modal');
    }

    public function removeCurrentImage()
    {
        $this->cover_img = '';
        $this->currentImageUrl = '';
    }

    public function save(ProductService $productService, CloudflareR2Service $r2Service)
    {
        try {
            $this->isUploading = true;
            $data = $this->validate();

            // Handle image upload
            if ($this->imageFile) {
                if (!$r2Service->validateImage($this->imageFile)) {
                    throw new \Exception('File gambar tidak valid atau terlalu besar (maksimal 5MB).');
                }

                $uploadedUrl = $r2Service->uploadFile($this->imageFile, 'products/product_images');

                if (!$uploadedUrl) {
                    throw new \Exception('Gagal mengupload gambar ke cloud storage.');
                }

                // Delete old image if editing and has old image
                if ($this->isEditing && $this->currentImageUrl && $this->currentImageUrl !== $uploadedUrl) {
                    $r2Service->deleteFile($this->currentImageUrl);
                }

                $data['cover_img'] = $uploadedUrl;
            } elseif (!$this->cover_img && $this->currentImageUrl) {
                // Image was removed
                if ($this->isEditing) {
                    $r2Service->deleteFile($this->currentImageUrl);
                }
                $data['cover_img'] = null;
            } else {
                // Keep existing image
                $data['cover_img'] = $this->cover_img ?: null;
            }

            if ($this->isEditing) {
                $productService->updateProduct($this->productId, $data);
                $message = 'Data produk berhasil diperbarui.';
            } else {
                $productService->createProduct($data);
                $message = 'Data produk berhasil dibuat.';
            }

            $this->resetForm();

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => $message,
                'timer' => 2500,
            ]);

            $this->dispatch('products:hide-modal');
            $this->dispatch('products:refresh-datatable');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => $this->isEditing ? 'Gagal memperbarui data produk.' : 'Gagal membuat produk baru.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        } finally {
            $this->isUploading = false;
        }
    }

    public function toggleProductStatus($productId, ProductService $productService)
    {
        try {
            $product = Product::findOrFail($productId);
            $newStatus = !$product->is_deleted;

            $productService->toggleProductStatus($productId, $newStatus);

            $statusText = $newStatus ? 'dihapus' : 'dipulihkan';

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => "Produk berhasil {$statusText}.",
                'timer' => 2500,
            ]);

            $this->dispatch('products:refresh-datatable');
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Gagal mengubah status produk.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function updateField($productId, $field, $value, ProductService $productService)
    {
        try {
            // Validate the field update
            $allowedFields = ['app_name', 'description', 'notes'];

            if (!in_array($field, $allowedFields)) {
                throw new \Exception('Field tidak valid.');
            }

            $validationRules = [
                'app_name' => 'required|string|max:200',
                'description' => 'required|string',
                'notes' => 'nullable|string|max:150',
            ];

            if (isset($validationRules[$field])) {
                $validator = validator([$field => $value], [$field => $validationRules[$field]]);

                if ($validator->fails()) {
                    throw new \Exception($validator->errors()->first($field));
                }
            }

            $productService->updateProductField($productId, $field, $value);

            $this->dispatch('products:field-updated', [
                'success' => true,
                'message' => 'Data berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('products:field-updated', [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->productId = null;
        $this->app_name = '';
        $this->description = '';
        $this->cover_img = '';
        $this->notes = '';
        $this->is_topup = false;
        $this->ready_stock = true;
        $this->category_id = '';
        $this->imageFile = null;
        $this->currentImageUrl = '';
        $this->resetValidation();

        $this->isEditing = false;
        $this->isUploading = false;
    }

    public function render()
    {
        return view('livewire.admin.master-data.product-data.products');
    }
}
