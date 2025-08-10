<?php

namespace App\Services\Client;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Services\Client\CartService;
use App\Services\Client\MidtransService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutService
{
    protected $cartService;
    protected $midtransService;

    public function __construct(CartService $cartService, MidtransService $midtransService)
    {
        $this->cartService = $cartService;
        $this->midtransService = $midtransService;
    }

    public function getRequiredFields($productName)
    {
        $productName = strtolower($productName);

        // Basic fields required for all products
        $fields = [
            'email' => 'Email'
        ];

        // Add specific fields based on product
        if (str_contains($productName, 'spotify')) {
            $fields['password'] = 'Password';
        } elseif (str_contains($productName, 'netflix')) {
            $fields['password'] = 'Password';
            $fields['profile_name'] = 'Profile Name';
        } elseif (str_contains($productName, 'adobe') || str_contains($productName, 'photoshop') || str_contains($productName, 'lightroom')) {
            $fields['password'] = 'Password';
            $fields['adobe_id'] = 'Adobe ID';
        } elseif (str_contains($productName, 'microsoft') || str_contains($productName, '365')) {
            $fields['password'] = 'Password';
            $fields['microsoft_account'] = 'Microsoft Account';
        } elseif (str_contains($productName, 'mobile legends') || str_contains($productName, 'diamonds')) {
            $fields['game_id'] = 'Game ID';
            $fields['server_id'] = 'Server ID';
        }

        return $fields;
    }

    public function getPaymentMethods()
    {
        return [
            'bank_transfer' => 'Bank Transfer',
            'credit_card' => 'Credit Card',
            'e_wallet' => 'E-Wallet (GoPay, OVO, DANA)',
            'qris' => 'QRIS'
        ];
    }

    public function createTransaction($checkoutData)
    {
        $userId = Auth::guard('web')->id();
        $cartSummary = $this->cartService->getCartSummary();

        if (empty($cartSummary['items'])) {
            throw new \Exception('Cart is empty');
        }

        return DB::transaction(function () use ($userId, $cartSummary, $checkoutData) {
            // Generate transaction code
            $transactionCode = 'ORD-' . strtoupper(Str::random(8));

            // Create transaction
            $transaction = Transaction::create([
                'user_id' => $userId,
                'transaction_code' => $transactionCode,
                'total_amount' => $cartSummary['subtotal'],
                'discount_amount' => 0,
                'final_amount' => $cartSummary['total'],
                'payment_method' => $checkoutData['payment_method'],
                'payment_status' => 'pending',
                'status' => $checkoutData['order_timing'] === 'now' ? 'processing' : 'pending',
                'customer_details' => $checkoutData['customer_details'] ?? [],
                'order_timing' => $checkoutData['order_timing'],
                'expired_at' => now()->addHours(24), // 24 hour expiry
            ]);

            // Create transaction details
            foreach ($cartSummary['items'] as $item) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_detail_id' => $item['product_detail_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['subtotal'],
                    'customer_data' => $checkoutData['items_data'][$item['id']] ?? [],
                    'status' => $checkoutData['order_timing'] === 'now' ? 'processing' : 'pending',
                ]);
            }

            // Create Midtrans payment URL for online payments
            if (in_array($checkoutData['payment_method'], ['credit_card', 'e_wallet', 'qris'])) {
                try {
                    $paymentUrl = $this->midtransService->createPaymentUrl($transaction);
                    $transaction->update(['payment_url' => $paymentUrl]);
                } catch (\Exception $e) {
                }
            }

            // Clear cart after successful transaction
            $this->cartService->clearCart();

            return $transaction;
        });
    }

    public function getTransactionById($transactionId)
    {
        $userId = Auth::guard('web')->id();

        return Transaction::with(['transactionDetails.productDetail.product.category', 'transactionDetails.productDetail.productType'])
            ->where('id', $transactionId)
            ->where('user_id', $userId)
            ->first();
    }

    public function getTransactionByCode($transactionCode)
    {
        $userId = Auth::guard('web')->id();

        return Transaction::with(['transactionDetails.productDetail.product.category', 'transactionDetails.productDetail.productType'])
            ->where('transaction_code', $transactionCode)
            ->where('user_id', $userId)
            ->first();
    }

    public function getUserTransactions()
    {
        $userId = Auth::guard('web')->id();

        return Transaction::with(['transactionDetails.productDetail.product'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');
    }

    public function formatPrice($price)
    {
        return 'Rp ' . number_format($price, 0, ',', '.');
    }
}
