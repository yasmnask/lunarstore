<?php

namespace App\Livewire\Client;

use App\Services\Client\CheckoutService;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Title('Order History')]
class OrderHistory extends Component
{
    use WithPagination;

    public $transactions = [];
    public $paymentMethods = [];
    public $selectedStatus = 'all';
    public $selectedPaymentStatus = 'all';

    protected $checkoutService;

    public function boot(CheckoutService $checkoutService)
    {
        $this->checkoutService = $checkoutService;
    }

    public function mount()
    {
        $this->paymentMethods = $this->checkoutService->getPaymentMethods();
        $this->loadTransactions();
    }

    public function loadTransactions()
    {
        $query = $this->checkoutService->getUserTransactions();

        // Filter by status
        if ($this->selectedStatus !== 'all') {
            $query = $query->where('status', $this->selectedStatus);
        }

        // Filter by payment status
        if ($this->selectedPaymentStatus !== 'all') {
            $query = $query->where('payment_status', $this->selectedPaymentStatus);
        }

        $this->transactions = $query->get();
    }

    public function updatedSelectedStatus()
    {
        $this->loadTransactions();
    }

    public function updatedSelectedPaymentStatus()
    {
        $this->loadTransactions();
    }

    public function viewOrder($transactionCode)
    {
        return redirect()->route('order.confirmation', ['code' => $transactionCode]);
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'text-yellow-600 bg-yellow-100',
            'processing' => 'text-blue-600 bg-blue-100',
            'completed' => 'text-green-600 bg-green-100',
            'cancelled' => 'text-red-600 bg-red-100',
            'failed' => 'text-red-600 bg-red-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    public function getPaymentStatusColor($paymentStatus)
    {
        return match ($paymentStatus) {
            'pending' => 'text-yellow-600 bg-yellow-100',
            'paid' => 'text-green-600 bg-green-100',
            'failed' => 'text-red-600 bg-red-100',
            'expired' => 'text-gray-600 bg-gray-100',
            default => 'text-gray-600 bg-gray-100'
        };
    }

    public function render()
    {
        return view('livewire.client.order-history');
    }
}
