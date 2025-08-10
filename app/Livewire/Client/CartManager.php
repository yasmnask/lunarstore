<?php

namespace App\Livewire\Client;

use App\Services\Client\CartService;
use Livewire\Attributes\Title;
use Livewire\Attributes\On;
use Livewire\Component;

#[Title('Shopping Cart')]
class CartManager extends Component
{
    public $cartItems = [];
    public $cartSummary = [];

    protected $cartService;

    public function boot(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function mount()
    {
        $this->loadCart();
    }

    public function loadCart()
    {
        $this->cartSummary = $this->cartService->getCartSummary();
        $this->cartItems = $this->cartSummary['items'];
    }

    public function updateQuantity($cartId, $quantity)
    {
        try {
            $this->cartService->updateQuantity($cartId, $quantity);
            $this->loadCart();
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to update item: ' . $e->getMessage());
        }
    }

    public function removeItem($cartId)
    {
        try {
            $this->cartService->removeItem($cartId);
            $this->loadCart();
            $this->dispatch('cart-updated');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to remove item: ' . $e->getMessage());
        }
    }

    public function clearCart()
    {
        try {
            $this->cartService->clearCart();
            $this->loadCart();
            $this->dispatch('cart-updated');
            $this->dispatch('cart-cleared');
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to clear cart: ' . $e->getMessage());
        }
    }

    #[On('cart-updated')]
    public function refreshCart()
    {
        $this->loadCart();
    }

    public function formatPrice($price)
    {
        return $this->cartService->formatPrice($price);
    }

    public function render()
    {
        return view('livewire.client.cart-manager');
    }

    public function proceedToCheckout()
    {
        if (empty($this->cartItems)) {
            return;
        }

        return redirect()->route('checkout');
    }
}
