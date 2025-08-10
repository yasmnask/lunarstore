<?php

namespace App\Services\Client;

use App\Models\Transaction;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;


class MidtransService
{
    public function __construct()
    {
        // Set Midtrans configuration
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production', false);
        Config::$isSanitized = config('midtrans.is_sanitized', true);
        Config::$is3ds = config('midtrans.is_3ds', true);
    }

    public function createPaymentUrl(Transaction $transaction)
    {
        $params = [
            'transaction_details' => [
                'order_id' => $transaction->transaction_code,
                'gross_amount' => (int) $transaction->final_amount,
            ],
            'customer_details' => [
                'first_name' => $transaction->user->name,
                'email' => $transaction->user->email,
                'phone' => $transaction->user->phone ?? '',
            ],
            'item_details' => $this->getItemDetails($transaction),
            'callbacks' => [
                'finish' => route('order.confirmation', ['code' => $transaction->transaction_code])
            ],
            'expiry' => [
                'start_time' => date('Y-m-d H:i:s O'),
                'unit' => 'hours',
                'duration' => 24
            ]
        ];

        try {
            // Create transaction and get redirect URL
            $snapResponse = Snap::createTransaction($params);

            // Update transaction with Midtrans data
            $transaction->update([
                'midtrans_order_id' => $transaction->transaction_code,
                'payment_url' => $snapResponse->redirect_url,
            ]);

            return $snapResponse->redirect_url;
        } catch (\Exception $e) {
            throw new \Exception('Failed to create payment URL: ' . $e->getMessage());
        }
    }

    protected function getItemDetails(Transaction $transaction)
    {
        $items = [];

        foreach ($transaction->transactionDetails as $detail) {
            $product = $detail->productDetail->product;
            $items[] = [
                'id' => (string) $detail->product_detail_id,
                'price' => (int) $detail->unit_price,
                'quantity' => (int) $detail->quantity,
                'name' => substr($product->app_name, 0, 50), // Limit name length
                'category' => $product->category->title ?? 'Digital Product',
            ];
        }

        return $items;
    }

    protected function getEnabledPayments($paymentMethod)
    {
        return match ($paymentMethod) {
            'credit_card' => ['credit_card'],
            'e_wallet' => ['gopay', 'shopeepay', 'dana', 'linkaja'],
            'qris' => ['qris'],
            'bank_transfer' => ['bca_va', 'bni_va', 'bri_va', 'permata_va', 'echannel'],
            default => ['credit_card', 'gopay', 'shopeepay', 'dana', 'qris', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'echannel']
        };
    }

    public function handleNotification()
    {
        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $fraudStatus = $notification->fraud_status ?? null;
            $orderId = $notification->order_id;

            $transaction = Transaction::where('transaction_code', $orderId)->first();

            if (!$transaction) {
                throw new \Exception('Transaction not found: ' . $orderId);
            }

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $transaction->update(['payment_status' => 'pending']);
                } else if ($fraudStatus == 'accept') {
                    $this->updateTransactionSuccess($transaction, $notification);
                }
            } else if ($transactionStatus == 'settlement') {
                $this->updateTransactionSuccess($transaction, $notification);
            } else if (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
                $transaction->update([
                    'payment_status' => 'failed',
                    'status' => 'cancelled'
                ]);

                // Update transaction details status
                $transaction->transactionDetails()->update([
                    'status' => 'cancelled'
                ]);
            } else if ($transactionStatus == 'pending') {
                $transaction->update(['payment_status' => 'pending']);
            }

            return ['status' => 'success'];
        } catch (\Exception $e) {
            return ['status' => 'error', 'message' => $e->getMessage()];
        }
    }

    protected function updateTransactionSuccess(Transaction $transaction, $notification)
    {
        $transaction->update([
            'payment_status' => 'paid',
            'status' => 'processing',
            'paid_at' => now(),
            'midtrans_transaction_id' => $notification->transaction_id ?? null,
        ]);

        // Update transaction details status
        $transaction->transactionDetails()->update([
            'status' => 'processing',
            'processed_at' => now()
        ]);
    }
}
