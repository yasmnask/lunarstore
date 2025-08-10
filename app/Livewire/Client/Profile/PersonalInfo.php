<?php

namespace App\Livewire\Client\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class PersonalInfo extends Component
{
    use WithFileUploads;

    public $user;
    public $username;
    public $name;
    public $email;
    public $phone;
    public $address;
    public $avatar;
    public $imagePreview;

    public $isWarningAuth = false;

    public function mount($user)
    {
        $this->user = $user;
        $this->username = $user['username'];
        $this->name = $user['name'];
        $this->email = $user['email'];
        $this->phone = $user['phone'];
        $this->address = $user['address'];
        $this->avatar = null;
        $this->imagePreview = Auth::guard('web')->user()->getAvatar();

        if (Auth::guard('web')->user()->username == '_g_' || Hash::check(env('DEFAULT_PASSWORD'), $user['password'])) {
            $this->isWarningAuth = true;
        }
    }

    public function updatedAvatar()
    {
        $this->validate([
            'avatar' => 'image|max:2048', // 2MB Max
        ]);

        $this->imagePreview = $this->avatar->temporaryUrl();
    }

    public function save()
    {
        dd([
            'username' => $this->username,
            'name' => $this->name,
            'email' => $this->email,
            'phone' => $this->phone,
            'address' => $this->address,
            'avatar' => $this->avatar
        ]);
    }

    public function cancelSave()
    {
        $this->name = ($this->user['name'] ?? '');
        $this->phone = ($this->user['phone'] ?? '');
        $this->address = ($this->user['address'] ?? '');
        $this->avatar = null;
        $this->imagePreview = ($this->user['avatar'] ?? null);
    }

    public function render()
    {
        return view('livewire.client.profile.personal-info');
    }
}
