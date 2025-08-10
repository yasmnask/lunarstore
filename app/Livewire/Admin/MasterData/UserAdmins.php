<?php

namespace App\Livewire\Admin\MasterData;

use App\Models\UserAdmin;
use App\Services\Admin\MasterData\UserAdminService;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

#[Title('Data Master Admin')]
#[Layout('layouts.admin')]
class UserAdmins extends Component
{
    // Form properties
    public $adminId = null;
    public $username = '';
    public $full_name = '';
    public $password = '';
    public $password_confirmation = '';

    // UI State
    public $isEditing = false;

    public function rules()
    {
        $rules = [
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('user_admins', 'username')->ignore($this->adminId)
            ],
            'full_name' => 'required|string|max:255',
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
        $this->dispatch('user_admins:show-add-modal');
        $this->resetForm();
    }

    public function openEditModal($id)
    {
        $admin = UserAdmin::findOrFail($id);
        $this->adminId = $admin->id;
        $this->username = $admin->username;
        $this->full_name = $admin->full_name;
        $this->isEditing = true;
        $this->dispatch('user_admins:show-add-modal');
    }

    public function save(UserAdminService $adminService)
    {
        try {
            $data = $this->validate();
            if ($this->isEditing) {
                $adminService->updateAdmin($this->adminId, $data);
                $message = 'Admin data updated successfully.';
                if (auth()->guard('admin')->id() == $this->adminId) {
                    $this->dispatch('admin-updated', [
                        'username' => $data['username'],
                        'full_name' => $data['full_name'],
                    ]);
                }
            } else {
                $adminService->createAdmin($data);
                $message = 'Admin data created successfully.';
            }
            $this->resetForm();

            $this->dispatch('swal-success', [
                'title' => 'Success!',
                'text' => $message,
                'timer' => 2500,
            ]);

            $this->dispatch('user_admins:hide-modal');
            $this->dispatch('user_admins:refresh-datatable');
        } catch (ValidationException $e) {
            $this->reset(['password', 'password_confirmation']);
            throw $e;
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => $this->isEditing ? 'Failed to update admin data.' : 'Failed to create new admin.',
                'text' => $e->getMessage(),
                'confirmButtonColor' => '#435ebe',
            ]);
        }
    }

    public function resetForm()
    {
        $this->adminId = null;
        $this->username = '';
        $this->full_name = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->resetValidation();

        $this->isEditing = false;
    }

    public function render()
    {
        return view('livewire.admin.master-data.user-admins');
    }
}
