<?php

namespace App\Livewire\Client;

use App\Services\Client\CheckoutService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Order Details')]
class OrderDetails extends Component
{
    public $transaction;
    public $paymentMethods = [];
    public $bankAccounts = [];

    protected $checkoutService;

    public function boot(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function mount($code)
    {
        $this->transaction = $this->checkoutService->getTransactionByCode($code);

        if (!$this->transaction) {
            session()->flash('error', 'Order not found');
            return redirect()->route('orders');
        }

        $this->paymentMethods = $this->checkoutService->getPaymentMethods();
        $this->bankAccounts = [
            [
                'bank_name' => 'Bank BCA',
                'account_number' => '1234567890',
                'account_name' => 'Lunar Store'
            ],
            [
                'bank_name' => 'Bank Mandiri',
                'account_number' => '0987654321',
                'account_name' => 'Lunar Store'
            ]
        ];
    }

    public function render()
    {
        return view('livewire.client.order-details');
    }
}
