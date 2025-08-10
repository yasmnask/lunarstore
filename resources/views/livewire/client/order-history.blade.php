<div>
    <!-- Page Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="lunar-text text-3xl font-bold text-blue-600">ORDER HISTORY</h1>
            <p class="text-gray-600 mt-2">Track and manage your orders</p>
        </div>
    </div>

    <!-- Filters Section -->
    <section class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Orders</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Order Status Filter -->
                    <div>
                        <label for="status-filter" class="block text-sm font-medium text-gray-700 mb-2">Order
                            Status</label>
                        <select wire:model.live="selectedStatus" id="status-filter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Status</option>
                            <option value="pending">Pending</option>
                            <option value="processing">Processing</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Payment Status Filter -->
                    <div>
                        <label for="payment-status-filter" class="block text-sm font-medium text-gray-700 mb-2">Payment
                            Status</label>
                        <select wire:model.live="selectedPaymentStatus" id="payment-status-filter"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            <option value="all">All Payment Status</option>
                            <option value="pending">Pending</option>
                            <option value="paid">Paid</option>
                            <option value="failed">Failed</option>
                            <option value="expired">Expired</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Orders List -->
    <section class="pb-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            @if (count($transactions) > 0)
                <div class="space-y-6">
                    @foreach ($transactions as $transaction)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden">
                            <div class="p-6">
                                <!-- Order Header -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                                    <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">Order
                                                #{{ $transaction->transaction_code }}</h3>
                                            <p class="text-sm text-gray-600">
                                                {{ $transaction->created_at->format('F j, Y \a\t g:i A') }}</p>
                                        </div>
                                    </div>
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center space-y-2 sm:space-y-0 sm:space-x-4">
                                        <!-- Order Status -->
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getStatusColor($transaction->status) }}">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                        <!-- Payment Status -->
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $this->getPaymentStatusColor($transaction->payment_status) }}">
                                            {{ ucfirst($transaction->payment_status) }}
                                        </span>
                                        <!-- Total Amount -->
                                        <span
                                            class="text-lg font-bold text-blue-600">{{ $transaction->formatted_total }}</span>
                                    </div>
                                </div>

                                <!-- Order Items Preview -->
                                <div class="border-t border-gray-200 pt-4">
                                    <div class="space-y-3">
                                        @foreach ($transaction->transactionDetails->take(3) as $detail)
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0 w-10 h-10">
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
                                                <div class="flex-1 min-w-0">
                                                    <p class="text-sm font-medium text-gray-900 truncate">
                                                        {{ $detail->productDetail->product->app_name }}</p>
                                                    <p class="text-xs text-gray-500">
                                                        {{ $detail->formatted_unit_price }} ×
                                                        {{ $detail->quantity }}</p>
                                                </div>
                                                <div class="text-sm font-medium text-gray-900">
                                                    {{ $detail->formatted_total_price }}
                                                </div>
                                            </div>
                                        @endforeach

                                        @if ($transaction->transactionDetails->count() > 3)
                                            <div class="text-sm text-gray-500 text-center py-2">
                                                +{{ $transaction->transactionDetails->count() - 3 }} more items
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Order Actions -->
                                <div class="border-t border-gray-200 pt-4 mt-4">
                                    <div
                                        class="flex flex-col sm:flex-row sm:items-center sm:justify-between space-y-2 sm:space-y-0">
                                        <div class="flex items-center space-x-4 text-sm text-gray-600">
                                            <span>
                                                <i class="fas fa-credit-card mr-1"></i>
                                                {{ $paymentMethods[$transaction->payment_method] ?? $transaction->payment_method }}
                                            </span>
                                            @if ($transaction->order_timing)
                                                <span>
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $transaction->order_timing === 'now' ? 'Immediate' : 'Scheduled' }}
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex space-x-2">
                                            <button wire:click="viewOrder('{{ $transaction->transaction_code }}')"
                                                class="bg-blue-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-blue-700 transition-colors flex items-center">
                                                <i class="fas fa-eye mr-1"></i>
                                                View Details
                                            </button>

                                            @if (
                                                $transaction->payment_status === 'pending' &&
                                                    in_array($transaction->payment_method, ['credit_card', 'e_wallet', 'qris']) &&
                                                    $transaction->payment_url)
                                                <a href="{{ $transaction->payment_url }}" target="_blank"
                                                    class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 transition-colors flex items-center">
                                                    <i class="fas fa-external-link-alt mr-1"></i>
                                                    Pay Now
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <div
                        class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 text-gray-400 mb-4">
                        <i class="fas fa-shopping-bag text-2xl"></i>
                    </div>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">No Orders Found</h3>
                    <p class="text-gray-600 mb-6">
                        @if ($selectedStatus !== 'all' || $selectedPaymentStatus !== 'all')
                            No orders match your current filters. Try adjusting your search criteria.
                        @else
                            You haven't placed any orders yet. Start shopping to see your orders here.
                        @endif
                    </p>
                    <div class="space-x-4">
                        @if ($selectedStatus !== 'all' || $selectedPaymentStatus !== 'all')
                            <button wire:click="$set('selectedStatus', 'all'); $set('selectedPaymentStatus', 'all')"
                                class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-medium hover:bg-gray-300 transition-colors">
                                Clear Filters
                            </button>
                        @endif
                        <a href="{{ route('catalog') }}"
                            class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
                            Start Shopping
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </section>
</div>
