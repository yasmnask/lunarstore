<?php

namespace App\Livewire\Client;

use App\Services\Client\CheckoutService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Order Confirmation')]
class OrderConfirmation extends Component
{
    public $transaction;
    public $paymentMethods = [];

    protected $checkoutService;

    public function boot(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function mount($code)
    {
        $this->transaction = $this->checkoutService->getTransactionByCode($code);

        if (!$this->transaction) {
            return redirect()->route('cart');
        }

        $this->paymentMethods = $this->checkoutService->getPaymentMethods();
    }

    public function payNow()
    {
        if (!$this->transaction->payment_url || $this->transaction->payment_status === 'paid') {
            return;
        }

        // Redirect to Midtrans payment page
        return redirect()->away($this->transaction->payment_url);
    }

    public function render()
    {
        return view('livewire.client.order-confirmation');
    }
}
