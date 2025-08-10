<?php

namespace App\Livewire\Client;

use App\Services\Client\CartService;
use App\Services\Client\CheckoutService;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Checkout')]
class CheckoutManager extends Component
{
    public $step = 1;
    public $cartItems = [];
    public $cartSummary = [];
    public $paymentMethods = [];
    public $selectedPaymentMethod = '';
    public $orderTiming = 'now';
    public $customerDetails = [];
    public $itemsData = [];
    public $isProcessing = false;

    protected $cartService;
    protected $checkoutService;

    public function boot(CartService $cartService, CheckoutService $checkoutService)
    {
        $this->cartService = $cartService;
        $this->checkoutService = $checkoutService;
    }

    public function mount()
    {
        $this->cartSummary = $this->cartService->getCartSummary();
        $this->cartItems = $this->cartSummary['items'];

        if (empty($this->cartItems)) {
            return redirect()->route('cart');
        }

        $this->paymentMethods = $this->checkoutService->getPaymentMethods();

        // Initialize customer details for each item
        foreach ($this->cartItems as $item) {
            $requiredFields = $this->checkoutService->getRequiredFields($item['product']['name']);
            foreach ($requiredFields as $field => $label) {
                $this->customerDetails[$item['id']][$field] = '';
            }
        }
    }

    public function getRequiredFieldsForItem($itemId)
    {
        $item = collect($this->cartItems)->firstWhere('id', $itemId);
        if (!$item) return [];

        return $this->checkoutService->getRequiredFields($item['product']['name']);
    }

    public function nextStep()
    {
        if ($this->step === 1) {
            $this->validateStep1();
            $this->step = 2;
        }
    }

    public function previousStep()
    {
        if ($this->step === 2) {
            $this->step = 1;
        }
    }

    protected function validateStep1()
    {
        $rules = [];

        // Validate customer details for each item
        foreach ($this->cartItems as $item) {
            $requiredFields = $this->getRequiredFieldsForItem($item['id']);
            foreach ($requiredFields as $field => $label) {
                $rules["customerDetails.{$item['id']}.{$field}"] = 'required';
            }
        }

        // Validate payment method
        $rules['selectedPaymentMethod'] = 'required|in:' . implode(',', array_keys($this->paymentMethods));

        $this->validate($rules, [
            'selectedPaymentMethod.required' => 'Please select a payment method',
            'customerDetails.*.*.required' => 'This field is required',
        ]);
    }

    public function confirmOrder()
    {
        if ($this->isProcessing) return;

        $this->isProcessing = true;

        try {
            $checkoutData = [
                'payment_method' => $this->selectedPaymentMethod,
                'order_timing' => $this->orderTiming,
                'customer_details' => $this->customerDetails,
                'items_data' => $this->customerDetails,
            ];

            $transaction = $this->checkoutService->createTransaction($checkoutData);

            session()->flash('success', 'Order created successfully!');

            return redirect()->route('order.confirmation', ['code' => $transaction->transaction_code]);
        } catch (\Exception $e) {
            session()->flash('error', 'Failed to create order: ' . $e->getMessage());
            $this->isProcessing = false;
        }
    }

    public function render()
    {
        return view('livewire.client.checkout-manager');
    }
}
