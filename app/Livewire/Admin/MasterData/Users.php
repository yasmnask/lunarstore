<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\User;
use App\Services\Admin\MasterData\UserService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

#[Title('Data Master Pengguna')]
#[Layout('layouts.admin')]
class Users extends Component
{
    // Form properties
    public $userId = null;
    public $username = '';
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $phone = '';
    public $address = '';

    // UI State
    public $isEditing = false;

    public function rules()
    {
        $rules = [
            'username' => [
                'required',
                'string',
                'min:4',
                'max:255',
                Rule::unique('users', 'username')->ignore($this->userId)
            ],
            'name' => 'required|string|min:6|max:255',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->userId)
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
        ];

        if (!$this->isEditing) {
            $rules['password'] = 'required|string|min:6';
            $rules['password_confirmation'] = 'required|confirmed:password';
        }

        return $rules;
    }

    public function mount()
    {
        $this->resetForm();
    }

    public function openAddModal()
    {
        $this->dispatch('users:show-add-modal');
        $this->resetForm();
    }

    public function openEditModal($id)
    {
        $user = User::findOrFail($id);
        $this->userId = $user->id;
        $this->username = $user->username;
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->address = $user->address ?? '';
        $this->isEditing = true;
        $this->dispatch('users:show-add-modal');
    }

    public function save(UserService $userService)
    {
        try {
            $data = $this->validate();

            if ($this->isEditing) {
                $userService->updateUser($this->userId, $data);
                $message = 'Data pengguna berhasil diperbarui.';
            } else {
                $userService->createUser($data);
                $message = 'Data pengguna berhasil dibuat.';
            }

            $this->resetForm();

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => $message,
                'timer' => 2500,
            ]);

            $this->dispatch('users:hide-modal');
            $this->dispatch('users:refresh-datatable');
        } catch (ValidationException $e) {
            $this->reset(['password', 'password_confirmation']);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => $this->isEditing ? 'Gagal memperbarui data pengguna.' : 'Gagal membuat pengguna baru.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function toggleUserStatus($userId, UserService $userService)
    {
        try {
            $user = User::findOrFail($userId);
            $newStatus = !$user->is_active;

            $userService->toggleUserStatus($userId, $newStatus);

            $statusText = $newStatus ? 'diaktifkan' : 'dinonaktifkan';

            $this->dispatch('swal-success', [
                'title' => 'Berhasil!',
                'text' => "Pengguna berhasil {$statusText}.",
                'timer' => 2500,
            ]);

            $this->dispatch('users:refresh-datatable');
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Gagal mengubah status pengguna.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function updateField($userId, $field, $value, UserService $userService)
    {
        try {
            // Validate the field update
            $allowedFields = ['username', 'name', 'email', 'phone', 'address'];

            if (!in_array($field, $allowedFields)) {
                throw new \Exception('Field tidak valid.');
            }

            $validationRules = [
                'username' => [
                    'required',
                    'string',
                    'min:4',
                    'max:255',
                    Rule::unique('users', 'username')->ignore($userId)
                ],
                'name' => 'required|string|min:6|max:255',
                'email' => [
                    'required',
                    'email',
                    'max:255',
                    Rule::unique('users', 'email')->ignore($userId)
                ],
                'phone' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ];

            if (isset($validationRules[$field])) {
                $validator = validator([$field => $value], [$field => $validationRules[$field]]);

                if ($validator->fails()) {
                    throw new \Exception($validator->errors()->first($field));
                }
            }

            $userService->updateUserField($userId, $field, $value);

            $this->dispatch('users:field-updated', [
                'success' => true,
                'message' => 'Data berhasil diperbarui.'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('users:field-updated', [
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function resetForm()
    {
        $this->userId = null;
        $this->username = '';
        $this->name = '';
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->phone = '';
        $this->address = '';
        $this->resetValidation();

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.admin.master-data.users');
    }
}
