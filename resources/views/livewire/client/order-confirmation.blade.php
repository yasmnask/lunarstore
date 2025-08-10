<div>
    <!-- Confirmation Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="lunar-text text-3xl font-bold text-blue-600">ORDER CONFIRMATION</h1>
            <p class="text-gray-600 mt-2">Thank you for your order!</p>
        </div>
    </div>

    <!-- Confirmation Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 sm:p-8">
                    <!-- Success Message -->
                    <div class="text-center mb-8">
                        <div
                            class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-500 mb-4">
                            <i class="fas fa-check-circle text-3xl"></i>
                        </div>
                        <h2 class="text-2xl font-bold text-gray-900">Order Placed Successfully!</h2>
                        <p class="text-gray-600 mt-2">
                            @if ($transaction->order_timing === 'now')
                                Your order has been placed and is being processed.
                            @else
                                Your order has been saved and will be processed later.
                            @endif
                        </p>
                    </div>

                    <!-- Order Details -->
                    <div class="border border-gray-200 rounded-lg p-6 mb-6">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Order Details</h3>
                            <span class="text-sm text-gray-600">Order #{{ $transaction->transaction_code }}</span>
                        </div>

                        <div class="space-y-4">
                            <!-- Order Items -->
                            @foreach ($transaction->transactionDetails as $detail)
                                <div class="flex items-start py-4 border-t border-gray-200">
                                    <div class="flex-shrink-0 w-12 h-12">
                                        @if ($detail->productDetail->product->cover_img)
                                            <img src="{{ $detail->productDetail->product->cover_img }}"
                                                alt="{{ $detail->productDetail->product->app_name }}"
                                                class="w-full h-full object-cover rounded">
                                        @else
                                            <img src="https://placehold.co/100x100?text={{ urlencode($detail->productDetail->product->app_name) }}"
                                                alt="{{ $detail->productDetail->product->app_name }}"
                                                class="w-full h-full object-cover rounded">
                                        @endif
                                    </div>
                                    <div class="ml-4 flex-1">
                                        <div class="flex justify-between">
                                            <div>
                                                <h4 class="text-sm font-medium text-gray-900">
                                                    {{ $detail->productDetail->product->app_name }}</h4>
                                                <p class="text-xs text-gray-500">
                                                    {{ $detail->productDetail->product->description }}</p>
                                                @if ($detail->productDetail->duration)
                                                    <p class="text-xs text-gray-500">
                                                        {{ $detail->productDetail->duration }}</p>
                                                @endif
                                            </div>
                                            <p class="text-sm font-medium text-gray-900">
                                                {{ $detail->formatted_unit_price }} × {{ $detail->quantity }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            <!-- Order Summary -->
                            <div class="border-t border-gray-200 pt-4">
                                <div class="flex justify-between">
                                    <span class="text-sm text-gray-600">Payment Method</span>
                                    <span
                                        class="text-sm text-gray-900">{{ $paymentMethods[$transaction->payment_method] ?? $transaction->payment_method }}</span>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-sm text-gray-600">Payment Status</span>
                                    <span
                                        class="text-sm font-medium {{ $transaction->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-sm text-gray-600">Order Status</span>
                                    <span
                                        class="text-sm font-medium {{ $transaction->order_timing === 'now' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ $transaction->order_timing === 'now' ? 'Processing' : 'Pending' }}
                                    </span>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-sm text-gray-600">Order Date</span>
                                    <span
                                        class="text-sm text-gray-900">{{ $transaction->created_at->format('F j, Y') }}</span>
                                </div>
                                <div class="flex justify-between mt-4 pt-4 border-t border-gray-200">
                                    <span class="text-base font-medium text-gray-900">Total</span>
                                    <span
                                        class="text-base font-bold text-blue-600">{{ $transaction->formatted_total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Section -->
                    @if ($transaction->payment_status === 'pending')
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Complete Your Payment</h3>

                            @if (in_array($transaction->payment_method, ['credit_card', 'e_wallet', 'qris']) && $transaction->payment_url)
                                <!-- Online Payment with Midtrans Redirect -->
                                <div class="space-y-4">
                                    <p class="text-sm text-gray-600">
                                        Click the button below to proceed to secure payment page:
                                    </p>
                                    <div class="text-center">
                                        <button wire:click="payNow"
                                            class="bg-blue-600 text-white px-8 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors inline-flex items-center">
                                            <i class="fas fa-external-link-alt mr-2"></i>
                                            Proceed to Payment - {{ $transaction->formatted_total }}
                                        </button>
                                    </div>
                                    <p class="text-xs text-gray-500 text-center">
                                        You will be redirected to Midtrans secure payment page
                                    </p>
                                </div>
                            @elseif ($transaction->payment_method === 'bank_transfer')
                                <!-- Bank Transfer Instructions -->
                                <div class="space-y-4">
                                    <p class="text-sm text-gray-600">
                                        Please complete your payment by transferring the total amount to one of our bank
                                        accounts:
                                    </p>
                                    <div class="bg-white p-4 rounded border border-gray-200">
                                        <p class="text-sm font-medium text-gray-900">Bank BCA</p>
                                        <p class="text-sm text-gray-600">Account Number: 1234567890</p>
                                        <p class="text-sm text-gray-600">Account Name: Lunar Store</p>
                                    </div>
                                    <div class="bg-white p-4 rounded border border-gray-200">
                                        <p class="text-sm font-medium text-gray-900">Bank Mandiri</p>
                                        <p class="text-sm text-gray-600">Account Number: 0987654321</p>
                                        <p class="text-sm text-gray-600">Account Name: Lunar Store</p>
                                    </div>
                                    <p class="text-sm text-gray-600">
                                        After completing the payment, please upload your payment proof in the order
                                        history page.
                                    </p>
                                </div>
                            @endif
                        </div>
                    @else
                        <!-- Payment Completed -->
                        <div class="bg-green-50 rounded-lg p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <i class="fas fa-check-circle text-green-400 text-xl"></i>
                                </div>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Payment Completed</h3>
                                    <p class="text-sm text-green-700 mt-1">
                                        Your payment has been successfully processed. Your order is now being prepared.
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="mt-8 flex justify-center space-x-4">
                        <a href="{{ route('orders') }}"
                            class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
                            View Order History
                        </a>
                        <a href="{{ route('catalog') }}"
                            class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-medium hover:bg-gray-300 transition-colors">
                            Continue Shopping
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
