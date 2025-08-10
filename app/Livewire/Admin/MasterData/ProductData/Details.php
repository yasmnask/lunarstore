<?php

namespace App\Livewire\Admin\MasterData\ProductData;

use App\Models\ProductDetail;
use App\Models\Product;
use App\Models\ProductType;
use App\Services\Admin\MasterData\ProductDetailService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

#[Title('Data Master Detail Produk')]
#[Layout('layouts.admin')]
class Details extends Component
{
    // Form properties
    public $detailId = null;
    public $product_id = '';
    public $product_type_id = '';
    public $duration = '';
    public $price = '';
    public $notes = '';

    // Multiple details creation
    public $bulkMode = false;
    public $bulkDetails = [];

    // UI State
    public $isEditing = false;
    public $products = [];
    public $productTypes = [];

    public function rules()
    {
        if ($this->bulkMode && !$this->isEditing) {
            return [
                'product_id' => 'required|exists:products,id',
                'product_type_id' => 'nullable|exists:product_types,id',
                'bulkDetails' => 'required|array|min:1',
                'bulkDetails.*.duration' => 'nullable|string|max:100',
                'bulkDetails.*.price' => 'required|integer|min:0',
                'bulkDetails.*.notes' => 'nullable|string|max:10',
            ];
        }

        $rules = [
            'product_id' => 'required|exists:products,id',
            'product_type_id' => 'nullable|exists:product_types,id',
            'duration' => 'nullable|string|max:100',
            'price' => 'required|integer|min:0',
            'notes' => 'nullable|string|max:10',
        ];

        return $rules;
    }

    protected $messages = [
        'bulkDetails.required' => 'Minimal harus ada satu detail yang dibuat.',
        'bulkDetails.*.price.required' => 'Harga wajib diisi.',
        'bulkDetails.*.price.integer' => 'Harga harus berupa angka.',
        'bulkDetails.*.price.min' => 'Harga tidak boleh kurang dari 0.',
        'bulkDetails.*.duration.max' => 'Durasi maksimal 100 karakter.',
    ];

    public function mount()
    {
        $this->loadProducts();
        $this->loadProductTypes();
        $this->resetForm();
    }

    public function loadProducts()
    {
        $this->products = Product::where('is_deleted', false)
            ->orderBy('app_name')
            ->get(['id', 'app_name']);
    }

    public function loadProductTypes()
    {
        $this->productTypes = ProductType::where('is_deleted', false)
            ->orderBy('type_name')
            ->get(['id', 'type_name']);
    }

    public function toggleBulkMode()
    {
        $this->bulkMode = !$this->bulkMode;
        $this->resetForm();

        if ($this->bulkMode) {
            $this->addBulkDetail();
        }
    }

    public function addBulkDetail()
    {
        $this->bulkDetails[] = [
            'duration' => '',
            'price' => '',
            'notes' => ''
        ];
    }

    public function removeBulkDetail($index)
    {
        if (count($this->bulkDetails) > 1) {
            unset($this->bulkDetails[$index]);
            $this->bulkDetails = array_values($this->bulkDetails);
        }
    }

    public function openAddModal()
    {
        $this->dispatch('details:show-add-modal');
        $this->resetForm();

        if ($this->bulkMode) {
            $this->addBulkDetail();
        }
    }

    public function openEditModal($id)
    {
        $detail = ProductDetail::with(['product', 'productType'])->findOrFail($id);
        $this->detailId = $detail->id;
        $this->product_id = $detail->product_id;
        $this->product_type_id = $detail->product_type_id;
        $this->duration = $detail->duration ?? '';
        $this->price = $detail->price;
        $this->notes = $detail->notes ?? '';
        $this->isEditing = true;
        $this->bulkMode = false; // Always single mode for editing
        $this->dispatch('details:show-add-modal');
    }

    public function save(ProductDetailService $detailService)
    {
        try {
            if ($this->bulkMode && !$this->isEditing) {
                // Bulk creation
                $this->validate();

                $createdCount = $detailService->createMultipleProductDetails(
                    $this->product_id,
                    $this->product_type_id,
                    $this->bulkDetails // Pass the entire bulkDetails array
                );
                $message = "{$createdCount} detail produk berhasil dibuat.";
            } else {
                // Single creation/update
                $data = $this->validate();

                if ($this->isEditing) {
                    $detailService->updateProductDetail($this->detailId, $data);
                    $message = 'Data detail produk berhasil diperbarui.';
                } else {
                    $detailService->createProductDetail($data);
                    $message = 'Data detail produk berhasil dibuat.';
                }
            }

            $this->resetForm();

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => $message,
                'timer' => 3000,
            ]);

            $this->dispatch('details:hide-modal');
            $this->dispatch('details:refresh-datatable');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => $this->isEditing ? 'Gagal memperbarui data detail produk.' : 'Gagal membuat detail produk baru.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function toggleDetailStatus($detailId, ProductDetailService $detailService)
    {
        try {
            $detail = ProductDetail::findOrFail($detailId);
            $newStatus = !$detail->is_deleted;

            $detailService->toggleDetailStatus($detailId, $newStatus);

            $statusText = $newStatus ? 'dihapus' : 'dipulihkan';

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => "Detail produk berhasil {$statusText}.",
                'timer' => 2500,
            ]);

            $this->dispatch('details:refresh-datatable');
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Gagal mengubah status detail produk.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function updateField($detailId, $field, $value, ProductDetailService $detailService)
    {
        try {
            // Validate the field update
            $allowedFields = ['duration', 'price', 'notes'];

            if (!in_array($field, $allowedFields)) {
                throw new \Exception('Field tidak valid.');
            }

            $validationRules = [
                'duration' => 'nullable|string|max:100',
                'price' => 'required|integer|min:0',
                'notes' => 'nullable|string|max:10',
            ];

            if (isset($validationRules[$field])) {
                $validator = validator([$field => $value], [$field => $validationRules[$field]]);

                if ($validator->fails()) {
                    throw new \Exception($validator->errors()->first($field));
                }
            }

            $detailService->updateDetailField($detailId, $field, $value);

            $this->dispatch('details:field-updated', [
                'success' => true,
                'message' => 'Data berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('details:field-updated', [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->detailId = null;
        $this->product_id = '';
        $this->product_type_id = '';
        $this->duration = '';
        $this->price = '';
        $this->notes = '';
        $this->bulkDetails = [];
        $this->resetValidation();

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.admin.master-data.product-data.details');
    }

    public function updatedProductId()
    {
        $this->product_type_id = ''; // Reset product type when product changes
        $this->loadProductTypesForProduct();
    }

    public function loadProductTypesForProduct()
    {
        if ($this->product_id) {
            // Get the selected product's app_name
            $product = Product::find($this->product_id);
            if ($product) {
                // Load product types that match this product's app_name
                $this->productTypes = ProductType::where('is_deleted', false)
                    ->where('app_name', $product->app_name)
                    ->orderBy('type_name')
                    ->get(['id', 'type_name']);
            } else {
                $this->productTypes = [];
            }
        } else {
            // If no product selected, load all product types
            $this->loadProductTypes();
        }
    }
}
