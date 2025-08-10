<?php

namespace App\Livewire\Client;

use App\Services\Client\CartService;
use Livewire\Attributes\On;
use Livewire\Component;

class CartCounter extends Component
{
    public $cartCount = 0;

    protected $cartService;

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function mount()
    {
        $this->updateCartCount();
    }

    #[On('cart-updated')]
    public function updateCartCount()
    {
        $this->cartCount = $this->cartService->getCartCount();
    }

    public function render()
    {
        return view('livewire.client.cart-counter');
    }
}
