<?php

namespace App\Livewire\Admin\MasterData\ProductData;

use App\Models\ProductType;
use App\Models\Product;
use App\Services\Admin\MasterData\ProductTypeService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

#[Title('Data Master Tipe Produk')]
#[Layout('layouts.admin')]
class Types extends Component
{
    // Form properties
    public $typeId = null;
    public $app_name = '';
    public $type_name = '';
    public $unit = '';
    public $description = '';

    // Multiple types creation
    public $bulkMode = false;
    public $bulkTypes = [];

    // UI State
    public $isEditing = false;
    public $appNames = [];

    public function rules()
    {
        if ($this->bulkMode && !$this->isEditing) {
            return [
                'app_name' => 'required|string|max:100',
                'bulkTypes' => 'required|array|min:1',
                'bulkTypes.*.type_name' => [
                    'required',
                    'string',
                    'max:75',
                    'distinct',
                    Rule::unique('product_types', 'type_name')
                ],
                'bulkTypes.*.unit' => 'nullable|string|max:50',
                'bulkTypes.*.description' => 'nullable|string',
            ];
        }

        $rules = [
            'app_name' => 'required|string|max:100',
            'type_name' => [
                'required',
                'string',
                'max:75',
                Rule::unique('product_types', 'type_name')->ignore($this->typeId)
            ],
            'unit' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ];

        return $rules;
    }

    protected $messages = [
        'bulkTypes.required' => 'Minimal harus ada satu tipe yang dibuat.',
        'bulkTypes.*.type_name.required' => 'Nama tipe wajib diisi.',
        'bulkTypes.*.type_name.distinct' => 'Nama tipe tidak boleh sama dalam satu form.',
        'bulkTypes.*.type_name.unique' => 'Nama tipe sudah digunakan.',
        'bulkTypes.*.type_name.max' => 'Nama tipe maksimal 75 karakter.',
        'bulkTypes.*.unit.max' => 'Unit maksimal 50 karakter.',
    ];

    public function mount()
    {
        $this->loadAppNames();
        $this->resetForm();
    }

    public function loadAppNames()
    {
        $this->appNames = Product::where('is_topup', true)
            ->where('is_deleted', false)
            ->orderBy('app_name')
            ->pluck('app_name')
            ->unique()
            ->values()
            ->toArray();
    }

    public function toggleBulkMode()
    {
        $this->bulkMode = !$this->bulkMode;
        $this->resetForm();

        if ($this->bulkMode) {
            $this->addBulkType();
        }
    }

    public function addBulkType()
    {
        $this->bulkTypes[] = [
            'type_name' => '',
            'unit' => '',
            'description' => ''
        ];
    }

    public function removeBulkType($index)
    {
        if (count($this->bulkTypes) > 1) {
            unset($this->bulkTypes[$index]);
            $this->bulkTypes = array_values($this->bulkTypes);
        }
    }

    public function openAddModal()
    {
        $this->dispatch('types:show-add-modal');
        $this->resetForm();

        if ($this->bulkMode) {
            $this->addBulkType();
        }
    }

    public function openEditModal($id)
    {
        $type = ProductType::findOrFail($id);
        $this->typeId = $type->id;
        $this->app_name = $type->app_name;
        $this->type_name = $type->type_name;
        $this->unit = $type->unit ?? '';
        $this->description = $type->description ?? '';
        $this->isEditing = true;
        $this->bulkMode = false; // Always single mode for editing
        $this->dispatch('types:show-add-modal');
    }

    public function save(ProductTypeService $typeService)
    {
        try {
            if ($this->bulkMode && !$this->isEditing) {
                // Bulk creation
                $this->validate();

                $createdCount = $typeService->createMultipleProductTypes($this->app_name, $this->bulkTypes);
                $message = "{$createdCount} tipe produk berhasil dibuat untuk aplikasi {$this->app_name}.";
            } else {
                // Single creation/update
                $data = $this->validate();

                if ($this->isEditing) {
                    $typeService->updateProductType($this->typeId, $data);
                    $message = 'Data tipe produk berhasil diperbarui.';
                } else {
                    $typeService->createProductType($data);
                    $message = 'Data tipe produk berhasil dibuat.';
                }
            }

            $this->resetForm();

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => $message,
                'timer' => 3000,
            ]);

            $this->dispatch('types:hide-modal');
            $this->dispatch('types:refresh-datatable');
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => $this->isEditing ? 'Gagal memperbarui data tipe produk.' : 'Gagal membuat tipe produk baru.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function toggleTypeStatus($typeId, ProductTypeService $typeService)
    {
        try {
            $type = ProductType::findOrFail($typeId);
            $newStatus = !$type->is_deleted;

            $typeService->toggleTypeStatus($typeId, $newStatus);

            $statusText = $newStatus ? 'dihapus' : 'dipulihkan';

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => "Tipe produk berhasil {$statusText}.",
                'timer' => 2500,
            ]);

            $this->dispatch('types:refresh-datatable');
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Gagal mengubah status tipe produk.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function updateField($typeId, $field, $value, ProductTypeService $typeService)
    {
        try {
            // Validate the field update
            $allowedFields = ['app_name', 'type_name', 'unit', 'description'];

            if (!in_array($field, $allowedFields)) {
                throw new \Exception('Field tidak valid.');
            }

            $validationRules = [
                'app_name' => 'required|string|max:100',
                'type_name' => [
                    'required',
                    'string',
                    'max:75',
                    Rule::unique('product_types', 'type_name')->ignore($typeId)
                ],
                'unit' => 'nullable|string|max:50',
                'description' => 'nullable|string',
            ];

            if (isset($validationRules[$field])) {
                $validator = validator([$field => $value], [$field => $validationRules[$field]]);

                if ($validator->fails()) {
                    throw new \Exception($validator->errors()->first($field));
                }
            }

            $typeService->updateTypeField($typeId, $field, $value);

            $this->dispatch('types:field-updated', [
                'success' => true,
                'message' => 'Data berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('types:field-updated', [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->typeId = null;
        $this->app_name = '';
        $this->type_name = '';
        $this->unit = '';
        $this->description = '';
        $this->bulkTypes = [];
        $this->resetValidation();

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.admin.master-data.product-data.types');
    }
}
