<?php

namespace App\Livewire\Client\Auth;

use Livewire\Component;

class GoogleLogin extends Component
{
    public string $currentType = 'login';

    public function render()
    {
        return view('livewire.client.auth.google-login');
    }

    public function redirectToGoogle()
    {
        return redirect()->route('google.login');
    }
}
