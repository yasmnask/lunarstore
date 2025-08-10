<?php

namespace App\Livewire\Client\Profile;

use Livewire\Component;

class Overview extends Component
{
    public $user;
    public $stats;

    public function mount($user, $stats)
    {
        $this->user = $user;
        $this->stats = $stats;
    }

    public function render()
    {
        return view('livewire.client.profile.overview');
    }
}
