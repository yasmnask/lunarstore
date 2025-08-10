<div>
    <!-- Checkout Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="lunar-text text-3xl font-bold text-blue-600">CHECKOUT</h1>
            <p class="text-gray-600 mt-2">Complete your purchase</p>
        </div>
    </div>

    <!-- Checkout Steps -->
    <div class="bg-white border-t border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex justify-center">
                <div class="flex items-center">
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center {{ $step >= 1 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                            <span class="text-sm font-medium">1</span>
                        </div>
                        <span
                            class="text-xs mt-1 {{ $step >= 1 ? 'text-blue-600 font-medium' : 'text-gray-500' }}">Details</span>
                    </div>
                    <div class="w-16 h-1 {{ $step >= 2 ? 'bg-blue-600' : 'bg-gray-200' }} mx-2"></div>
                    <div class="flex flex-col items-center">
                        <div
                            class="w-10 h-10 rounded-full flex items-center justify-center {{ $step >= 2 ? 'bg-blue-600 text-white' : 'bg-gray-200 text-gray-600' }}">
                            <span class="text-sm font-medium">2</span>
                        </div>
                        <span
                            class="text-xs mt-1 {{ $step >= 2 ? 'text-blue-600 font-medium' : 'text-gray-500' }}">Review</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Checkout Content -->
    <section class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="lg:grid lg:grid-cols-12 lg:gap-8">
                <!-- Main Content -->
                <div class="lg:col-span-8">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden mb-6">
                        @if ($step === 1)
                            <!-- Step 1: Enter Details -->
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Enter Your Details</h2>

                                <div class="space-y-6">
                                    @foreach ($cartItems as $item)
                                        <div class="border-b border-gray-200 pb-6">
                                            <h3 class="text-lg font-medium text-gray-900 mb-3">
                                                {{ $item['product']['name'] }}</h3>

                                            @php
                                                $fields = $this->getRequiredFieldsForItem($item['id']);
                                            @endphp

                                            @foreach ($fields as $fieldName => $fieldLabel)
                                                <div class="mb-3">
                                                    <label for="item_{{ $item['id'] }}_{{ $fieldName }}"
                                                        class="block text-sm font-medium text-gray-700 mb-1">
                                                        {{ $fieldLabel }}
                                                    </label>
                                                    <input type="{{ $fieldName === 'password' ? 'password' : 'text' }}"
                                                        id="item_{{ $item['id'] }}_{{ $fieldName }}"
                                                        wire:model="customerDetails.{{ $item['id'] }}.{{ $fieldName }}"
                                                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-blue-600 focus:border-blue-600"
                                                        required>
                                                    @error("customerDetails.{$item['id']}.{$fieldName}")
                                                        <span class="text-red-500 text-xs">{{ $message }}</span>
                                                    @enderror
                                                </div>
                                            @endforeach
                                        </div>
                                    @endforeach

                                    <!-- Payment Method Selection -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-3">Payment Method</h3>

                                        @foreach ($paymentMethods as $value => $label)
                                            <div class="mb-2">
                                                <label class="flex items-center">
                                                    <input type="radio" wire:model="selectedPaymentMethod"
                                                        value="{{ $value }}"
                                                        class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300"
                                                        required>
                                                    <span class="ml-2 text-sm text-gray-700">{{ $label }}</span>
                                                </label>
                                            </div>
                                        @endforeach
                                        @error('selectedPaymentMethod')
                                            <span class="text-red-500 text-xs">{{ $message }}</span>
                                        @enderror
                                    </div>

                                    <!-- Schedule Order -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-3">Order Timing</h3>

                                        <div class="mb-2">
                                            <label class="flex items-center">
                                                <input type="radio" wire:model="orderTiming" value="now"
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300">
                                                <span class="ml-2 text-sm text-gray-700">Process order now</span>
                                            </label>
                                        </div>

                                        <div class="mb-2">
                                            <label class="flex items-center">
                                                <input type="radio" wire:model="orderTiming" value="later"
                                                    class="h-4 w-4 text-blue-600 focus:ring-blue-600 border-gray-300">
                                                <span class="ml-2 text-sm text-gray-700">Process order later
                                                    (pending)</span>
                                            </label>
                                        </div>
                                    </div>

                                    <div class="flex justify-between">
                                        <a href="{{ route('cart') }}"
                                            class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-medium hover:bg-gray-300 transition-colors">
                                            Back to Cart
                                        </a>
                                        <button type="button" wire:click="nextStep"
                                            class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
                                            Continue to Review
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @elseif ($step === 2)
                            <!-- Step 2: Review Order -->
                            <div class="p-6">
                                <h2 class="text-xl font-bold text-gray-900 mb-4">Review Your Order</h2>

                                <div class="space-y-6">
                                    <!-- Order Details -->
                                    @foreach ($cartItems as $item)
                                        <div class="border-b border-gray-200 pb-6">
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 w-16 h-16">
                                                    @if ($item['product']['cover_img'])
                                                        <img src="{{ $item['product']['cover_img'] }}"
                                                            alt="{{ $item['product']['name'] }}"
                                                            class="w-full h-full object-cover rounded">
                                                    @else
                                                        <img src="https://placehold.co/100x100?text={{ $item['product']['name'] }}"
                                                            alt="{{ $item['product']['name'] }}"
                                                            class="w-full h-full object-cover rounded">
                                                    @endif
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h3 class="text-lg font-medium text-gray-900">
                                                        {{ $item['product']['name'] }}</h3>
                                                    <p class="text-sm text-gray-600">
                                                        {{ $item['product']['description'] }}</p>
                                                    @if ($item['plan'])
                                                        <p class="text-xs text-gray-500">{{ $item['plan']['name'] }}
                                                        </p>
                                                    @endif
                                                    <p class="text-sm font-medium text-blue-600 mt-1">
                                                        {{ $item['formatted_price'] }} × {{ $item['quantity'] }}</p>
                                                </div>
                                            </div>

                                            <div class="mt-4 bg-gray-50 p-4 rounded-md">
                                                <h4 class="text-sm font-medium text-gray-900 mb-2">Account Details:</h4>
                                                <ul class="text-sm text-gray-600 space-y-1">
                                                    @php
                                                        $fields = $this->getRequiredFieldsForItem($item['id']);
                                                    @endphp
                                                    @foreach ($fields as $fieldName => $fieldLabel)
                                                        <li>
                                                            <strong>{{ $fieldLabel }}:</strong>
                                                            {{ $fieldName === 'password' ? str_repeat('•', strlen($this->customerDetails[$item['id']][$fieldName] ?? '')) : $this->customerDetails[$item['id']][$fieldName] ?? '' }}
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    @endforeach

                                    <!-- Payment Method -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Method</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $paymentMethods[$selectedPaymentMethod] ?? '' }}
                                        </p>
                                    </div>

                                    <!-- Order Timing -->
                                    <div>
                                        <h3 class="text-lg font-medium text-gray-900 mb-2">Order Timing</h3>
                                        <p class="text-sm text-gray-600">
                                            {{ $orderTiming === 'now' ? 'Process order now' : 'Process order later (pending)' }}
                                        </p>
                                    </div>

                                    <div class="flex justify-between mt-6">
                                        <button type="button" wire:click="previousStep"
                                            class="bg-gray-200 text-gray-800 px-6 py-3 rounded-md font-medium hover:bg-gray-300 transition-colors">
                                            Back to Details
                                        </button>
                                        <button type="button" wire:click="confirmOrder"
                                            class="bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors disabled:opacity-50"
                                            {{ $isProcessing ? 'disabled' : '' }}>
                                            @if ($isProcessing)
                                                <i class="fas fa-spinner fa-spin mr-2"></i>
                                                Processing...
                                            @else
                                                Confirm Order
                                            @endif
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-lg shadow-md overflow-hidden sticky top-24">
                        <div class="p-6">
                            <h2 class="text-lg font-bold text-gray-900 mb-4">Order Summary</h2>

                            <div class="space-y-4">
                                <!-- Items -->
                                @foreach ($cartItems as $item)
                                    <div class="flex justify-between text-sm">
                                        <span class="text-gray-600">
                                            {{ $item['product']['name'] }} × {{ $item['quantity'] }}
                                        </span>
                                        <span class="font-medium">{{ $item['formatted_subtotal'] }}</span>
                                    </div>
                                @endforeach

                                <!-- Divider -->
                                <div class="border-t border-gray-200 my-4"></div>

                                <!-- Total -->
                                <div class="flex justify-between">
                                    <span class="font-medium text-gray-900">Total</span>
                                    <span class="font-bold text-blue-600">{{ $cartSummary['formatted_total'] }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
