<?php

namespace App\Livewire\Client;

use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Premium Digital Products')]

class Aboutus extends Component
{
    public function render()
    {
        return view('livewire.client.aboutus');
    }
}
