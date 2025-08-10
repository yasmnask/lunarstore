<?php

namespace App\Livewire\Admin;

use App\Services\Admin\AuthService as AdminAuthService;
use App\Traits\HasFlashMessages;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin Login')]
#[Layout('layouts.admin')]

class Login extends Component
{
    use HasFlashMessages;

    public $username;
    public $password;
    public $remember = false;

    public function mount()
    {
        if (session()->has('swal-auth')) {
            $this->dispatch('show-swal', session('swal-auth'));
        }
    }

    /**
     * Handle the login action.
     */
    public function login(AdminAuthService $auth)
    {
        $this->clearFlash();
        $validated = $this->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
            'remember' => ['boolean'],
        ]);

        try {
            $auth->login($validated);

            session()->flash('swal-auth', [
                'title' => 'Successfully Logged In',
                'icon' => 'success',
                'text' => 'Login successful! Welcome to Lunar Store Admin Dashboard.',
            ]);

            $this->redirectIntended('/admin', true);
        } catch (ValidationException $e) {
            $this->reset([
                'password',
                'remember',
            ]);

            $this->flashError(
                $e->getMessage()
            );
        } catch (\Exception $e) {
            $this->flashError(
                'An unexpected error occurred. Please try again later.'
            );
        } finally {
            $this->resetValidation();
        }
    }

    public function render()
    {

        return view('livewire.admin.login');
    }
}
