<div>
    <!-- Order Details Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex items-center">
                <a href="" class="text-gray-500 hover:text-blue-600 mr-2">
                    <i class="fas fa-arrow-left"></i>
                </a>
                <div>
                    <h1 class="lunar-text text-3xl font-bold text-blue-600">ORDER DETAILS</h1>
                    <p class="text-gray-600 mt-2">Order #{{ $transaction->transaction_code }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Details Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-md overflow-hidden">
                <div class="p-6 sm:p-8">
                    <!-- Order Status -->
                    <div class="flex justify-between items-center mb-6">
                        <div>
                            <span class="text-sm text-gray-600">Order Date:
                                {{ $transaction->created_at->format('Y-m-d') }}</span>
                        </div>
                        <div class="flex space-x-2">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->status_badge_class }}">
                                {{ ucfirst($transaction->status) }}
                            </span>
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $transaction->payment_status_badge_class }}">
                                {{ ucfirst($transaction->payment_status) }}
                            </span>
                        </div>
                    </div>

                    <!-- Order Items -->
                    <div class="border border-gray-200 rounded-lg p-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Order Items</h3>

                        <div class="space-y-4">
                            @foreach ($transaction->transactionDetails as $index => $detail)
                                <div class="flex items-start py-4 {{ $index > 0 ? 'border-t border-gray-200' : '' }}">
                                    <div class="flex-shrink-0 w-12 h-12">
                                        @if ($detail->productDetail->product->cover_img)
                                            <img src="{{ $detail->productDetail->product->cover_img }}"
                                                alt="{{ $detail->productDetail->product->app_name }}"
                                                class="w-full h-full object-cover rounded">
                                        @else
                                            <img src="https://placehold.co/100x100?text={{ $detail->productDetail->product->app_name }}"
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
                                    <span class="text-sm text-gray-600">Order Status</span>
                                    <span
                                        class="text-sm font-medium {{ $transaction->status === 'completed' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ ucfirst($transaction->status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-sm text-gray-600">Payment Status</span>
                                    <span
                                        class="text-sm font-medium {{ $transaction->payment_status === 'paid' ? 'text-green-600' : 'text-yellow-600' }}">
                                        {{ ucfirst($transaction->payment_status) }}
                                    </span>
                                </div>
                                <div class="flex justify-between mt-2">
                                    <span class="text-sm text-gray-600">Order Date</span>
                                    <span
                                        class="text-sm text-gray-900">{{ $transaction->created_at->format('Y-m-d') }}</span>
                                </div>
                                <div class="flex justify-between mt-4 pt-4 border-t border-gray-200">
                                    <span class="text-base font-medium text-gray-900">Total</span>
                                    <span
                                        class="text-base font-bold text-blue-600">{{ $transaction->formatted_total }}</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Information -->
                    @if ($transaction->payment_status === 'pending')
                        <div class="bg-gray-50 rounded-lg p-6">
                            <h3 class="text-lg font-medium text-gray-900 mb-4">Payment Information</h3>

                            @if ($transaction->payment_method === 'bank_transfer')
                                <div class="space-y-4">
                                    <p class="text-sm text-gray-600">
                                        Please complete your payment by transferring the total amount to one of our bank
                                        accounts:
                                    </p>
                                    @foreach ($bankAccounts as $account)
                                        <div class="bg-white p-4 rounded border border-gray-200">
                                            <p class="text-sm font-medium text-gray-900">{{ $account['bank_name'] }}
                                            </p>
                                            <p class="text-sm text-gray-600">Account Number:
                                                {{ $account['account_number'] }}</p>
                                            <p class="text-sm text-gray-600">Account Name:
                                                {{ $account['account_name'] }}</p>
                                        </div>
                                    @endforeach
                                    <p class="text-sm text-gray-600">
                                        After completing the payment, your order will be automatically processed once
                                        payment is verified.
                                    </p>
                                </div>
                            @elseif ($transaction->payment_method === 'qris')
                                <div class="space-y-4">
                                    <p class="text-sm text-gray-600">
                                        Please scan the QRIS code below to complete your payment:
                                    </p>
                                    <div class="flex justify-center">
                                        <img src="https://placehold.co/200x200?text=QRIS+Code" alt="QRIS Code"
                                            class="w-48 h-48">
                                    </div>
                                    <p class="text-sm text-gray-600 text-center">
                                        Your payment will be automatically verified once completed.
                                    </p>
                                </div>
                            @elseif (in_array($transaction->payment_method, ['credit_card', 'e_wallet']) && $transaction->payment_url)
                                <div class="space-y-4">
                                    <p class="text-sm text-gray-600">
                                        Click the button below to complete your payment:
                                    </p>
                                    <div class="text-center">
                                        <a href="{{ $transaction->payment_url }}" target="_blank"
                                            class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
                                            Complete Payment
                                        </a>
                                    </div>
                                </div>
                            @else
                                <p class="text-sm text-gray-600">
                                    Your payment is being processed. Once verified, your order will be completed
                                    automatically.
                                </p>
                            @endif
                        </div>
                    @endif

                    <div class="mt-8 flex justify-center">
                        <a href=""
                            class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
                            Back to Order History
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
