<?php

namespace App\Livewire\Client\Auth;

use App\Services\Client\AuthService as ClientAuthService;
use App\Traits\HasFlashMessages;
use Livewire\Attributes\Title;
use Livewire\Component;
use Illuminate\Validation\ValidationException;

class Login extends Component
{
    use HasFlashMessages;

    #[Title('Login')]

    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    public function mount()
    {
        if (session()->has('swal-auth')) {
            $this->dispatch('show-swal', session('swal-auth'));
        }
    }

    public function login(ClientAuthService $auth)
    {
        $this->clearFlash();
        $validated = $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember' => ['boolean'],
        ]);

        try {
            $auth->login($validated);

            session()->flash('swal-auth', [
                'title' => 'Successfully Logged In',
                'icon' => 'success',
                'text' => 'Login successful! Welcome to Lunar Store.',
            ]);

            $this->redirectIntended('/', true);
        } catch (ValidationException $e) {
            $this->reset([
                'password',
                'remember',
            ]);

            $this->flashWithTimeout(
                $e->getMessage(),
                'error'
            );
        } catch (\Exception $e) {
            $this->flashWithTimeout(
                'An unexpected error occurred. Please try again later.',
                'error'
            );
        } finally {
            $this->resetValidation();
        }
    }

    public function render()
    {
        return view('livewire.client.auth.login');
    }
}
