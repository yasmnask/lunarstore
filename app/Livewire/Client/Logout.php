<?php

namespace App\Livewire\Client;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public function logout()
    {
        // show sweet alert before logout
        $this->dispatch('swal-confirm', [
            'icon' => 'warning',
            'title' => 'Are you sure?',
            'text' => 'You are about to log out.',
            'confirmButtonColor' => '#435ebe',
            'cancelButtonColor' => '#d33',
            'confirmButtonText' => 'Yes, logout!',
            'action' => 'performLogout',
        ]);
    }

    public function performLogout()
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        session()->flash('swal-auth', [
            'icon' => 'success',
            'title' => 'Logged out successfully!',
            'text' => 'You have been logged out.',
        ]);
        $this->redirectRoute('login', [], true, true);
    }

    public function render()
    {
        return view('livewire.client.logout');
    }
}
