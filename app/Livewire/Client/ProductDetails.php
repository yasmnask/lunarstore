<?php

namespace App\Livewire\Client;

use App\Services\Client\CartService;
use App\Services\Client\ProductService;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

use Livewire\Attributes\On;

#[Title('Product Details')]
class ProductDetails extends Component
{
    public $productId;
    public $product = null;

    public $selectedPlan = null;
    public $selectedDuration = null;
    public $selectedPrice = 0;

    public $selectedProductDetailId;
    public $selectedQuantity = 1;

    protected $productService;

    public function boot(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function mount($id)
    {
        $this->productId = $id;
        $this->loadProduct();
    }

    public function loadProduct()
    {
        $this->product = $this->productService->getProductDetails($this->productId);
    }

    #[On('set-cart-data')]
    public function setCartData($detailId, $quantity)
    {
        $this->selectedProductDetailId = $detailId;
        $this->selectedQuantity = $quantity;
    }

    #[On('add-to-cart')]
    public function addToCart(CartService $cartService)
    {
        try {
            if (!$this->selectedProductDetailId) {
                $this->dispatch('show-error', message: 'Please select a duration');
                return;
            }

            if (!Auth::guard('web')->check()) {
                $this->dispatch('show-error', message: 'Please login to add items to cart');
                return;
            }

            $cartService->addToCart([
                'product_detail_id' => $this->selectedProductDetailId,
                'quantity' => $this->selectedQuantity
            ]);

            $this->dispatch('cart-updated');
            $this->dispatch('show-success', message: 'Item added to cart successfully');
        } catch (\Exception $e) {
            $this->dispatch('show-error', message: 'Failed to add item to cart');
        }
    }

    public function formatPrice($price)
    {
        return $this->productService->formatPrice($price);
    }

    public function render()
    {
        return view('livewire.client.product-details');
    }
}
