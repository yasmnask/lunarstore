<?php

namespace App\Livewire\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Logout extends Component
{
    public $type;

    public function mount($type = 'header')
    {
        $this->type = $type;
    }

    public function performLogout()
    {
        Auth::guard('admin')->logout();
        session()->invalidate();
        session()->regenerateToken();

        session()->flash('swal-auth', [
            'icon' => 'success',
            'title' => 'Logged out successfully!',
            'text' => 'You have been logged out.',
        ]);
        $this->redirectRoute('admin.login', [], true, true);
    }

    public function render()
    {
        return view('livewire.admin.logout');
    }
}
