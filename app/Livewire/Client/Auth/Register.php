<?php

namespace App\Livewire\Client\Auth;

use App\Services\Client\AuthService as ClientAuthService;
use App\Traits\HasFlashMessages;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Title;

class Register extends Component
{
    use HasFlashMessages;

    #[Title('Register')]

    public string $email = '';
    public string $password = '';
    public string $username = '';
    public string $name = '';
    public string $confirm_password = '';
    public bool $terms = false;

    public function render()
    {
        return view('livewire.client.auth.register');
    }

    public function register(ClientAuthService $auth)
    {
        $this->clearFlash();
        $validated = $this->validate([
            'username' => ['required', 'string', 'min:4', 'alpha_dash', 'unique:users,username'],
            'name' => ['required', 'string', 'min:6'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:6'],
            'confirm_password' => ['required', 'min:6', 'confirmed:password'],
        ], [
            'username.unique' => 'This username is already taken',
            'email.unique' => 'This email address is already registered',
        ]);

        try {
            $validated['terms'] = $this->terms;

            $auth->register($validated);

            session()->flash('swal-auth', [
                'title' => 'Account Created Successfully',
                'icon' => 'success',
                'text' => 'Registration successful! Welcome to Lunar Store.',
            ]);

            $this->redirectIntended('/', true);
        } catch (ValidationException $e) {
            $this->reset([
                'password',
                'confirm_password',
                'terms',
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
}
