@push('styles')
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
            --primary-blue-light: #3b82f6;
            --accent-blue: #60a5fa;
            --success-green: #10b981;
            --warning-amber: #f59e0b;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }

        .product-image-container {
            position: relative;
            overflow: hidden;
            background: var(--gray-50);
            aspect-ratio: 1;
        }

        .product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .product-image:hover {
            transform: scale(1.05);
        }

        .category-badge {
            display: inline-block;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-light));
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.875rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }

        .product-note {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #f59e0b;
            border-radius: 12px;
            padding: 1rem;
            margin-top: 1rem;
        }

        .product-note-content {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }

        .product-note-icon {
            color: var(--warning-amber);
            font-size: 1.25rem;
            margin-top: 0.125rem;
        }

        .product-note-text {
            color: #92400e;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .plans-section {
            background: white;
            border-radius: 12px;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            overflow: hidden;
            border: 1px solid var(--gray-100);
            padding: 30px;
        }

        .duration-option {
            transition: all 0.2s ease;
            cursor: pointer;
            border: 1px solid #e5e7eb;
            background-color: white;
            user-select: none;
            -webkit-user-select: none;
        }

        .duration-option:hover {
            border-color: #004aad;
        }

        .duration-option.selected {
            background-color: #e6f0ff;
            border-color: #004aad;
        }

        .add-to-cart-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 8px 15px -3px rgba(37, 99, 235, 0.4);
        }

        .add-to-cart-btn:disabled {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .add-to-cart-btn.success-animation {
            animation: successPulse 0.6s ease;
        }

        @keyframes successPulse {
            0% {
                background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            }

            50% {
                background: linear-gradient(135deg, var(--success-green), #059669);
            }

            100% {
                background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            }
        }

        .plan-card {
            transition: all 0.3s ease;
        }

        .plan-card:hover {
            transform: translateY(-5px);
        }

        .plan-card.selected {
            border-color: #004aad;
            background-color: rgba(0, 74, 173, 0.05);
        }

        /* Modal Styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.6);
            z-index: 1000;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            backdrop-filter: blur(4px);
        }

        .modal-overlay.active {
            opacity: 1;
            visibility: visible;
        }

        .modal-content {
            background: white;
            border-radius: 20px;
            padding: 2rem;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transform: translateY(20px) scale(0.95);
            transition: all 0.3s ease;
        }

        .modal-overlay.active .modal-content {
            transform: translateY(0) scale(1);
        }

        .modal-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .modal-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .modal-subtitle {
            color: var(--gray-600);
        }

        .order-summary {
            background: var(--gray-50);
            border-radius: 12px;
            padding: 1.5rem;
            margin-bottom: 2rem;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.75rem;
        }

        .summary-row:last-child {
            margin-bottom: 0;
            padding-top: 0.75rem;
            border-top: 1px solid var(--gray-200);
            font-weight: 600;
        }

        .summary-label {
            color: var(--gray-600);
        }

        .summary-value {
            font-weight: 500;
            color: var(--gray-900);
        }

        .total-price {
            font-size: 1.25rem;
            color: var(--primary-blue);
        }

        .modal-actions {
            display: flex;
            gap: 1rem;
            justify-content: flex-end;
        }

        .btn-secondary {
            background: white;
            border: 2px solid var(--gray-300);
            color: var(--gray-700);
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-secondary:hover {
            border-color: var(--gray-400);
            background: var(--gray-50);
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            border: none;
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -2px rgba(37, 99, 235, 0.3);
        }

        .success-icon {
            width: 4rem;
            height: 4rem;
            background: linear-gradient(135deg, var(--success-green), #059669);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            color: white;
            font-size: 1.5rem;
        }

        .success-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .not-found {
            text-align: center;
            padding: 4rem 2rem;
        }

        .not-found-icon {
            font-size: 4rem;
            color: var(--gray-400);
            margin-bottom: 1rem;
        }

        .not-found h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }

        .not-found p {
            color: var(--gray-600);
            margin-bottom: 2rem;
        }

        .btn-link {
            background: var(--primary-blue);
            color: white;
            padding: 0.75rem 1.5rem;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.2s ease;
            display: inline-block;
        }

        .btn-link:hover {
            background: var(--primary-blue-dark);
            transform: translateY(-1px);
        }

        .plan-card,
        .duration-card,
        .duration-card-large {
            cursor: pointer;
        }

        .plan-card *,
        .duration-card *,
        .duration-card-large * {
            cursor: inherit;
        }
    </style>
@endpush

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    @include('components.client.notification')
    @if ($product)
        <!-- Product Header -->
        <div class="bg-white rounded-lg shadow-md overflow-hidden mb-8">
            <div class="p-6 flex flex-col md:flex-row items-center">
                <div class="product-image-container rounded-xl md:w-1/4 flex justify-center mb-6 md:mb-0">
                    @if ($product['cover_img'])
                        <img src="{{ $product['cover_image'] }}" alt="{{ $product['app_name'] }}" class="product-image"
                            onerror="this.src='https://placehold.co/400x400?text={{ $product['app_name'] }}'">
                    @else
                        <img src="https://placehold.co/400x400?text={{ $product['app_name'] }}"
                            alt="{{ $product['app_name'] }}" class="product-image">
                    @endif
                </div>

                <div class="md:w-3/4 md:pl-8">
                    <h1 class="text-3xl font-bold text-black mb-3">{{ $product['app_name'] }}</h1>
                    <div class="category-badge">{{ $product['category'] }}</div>
                    <p class="text-gray-600 mb-4 text-[18px]">{{ $product['description'] }}</p>

                    @if ($product['notes'])
                        <div class="product-note">
                            <div class="product-note-content">
                                <i class="fas fa-exclamation-triangle product-note-icon"></i>
                                <div class="product-note-text">
                                    <strong>Catatan:</strong> {{ $product['notes'] }}
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        {{-- 
        @php
            dd($product);
        @endphp --}}

        <!-- Plans Section -->
        <div class="plans-section">
            <div class="flex justify-between items-center mb-8">
                <div>
                    <h2 class="text-xl md:text-2xl font-bold text-blue-600 text-playfair">Choose Your Plan</h2>
                    <div class="bg-blue-600 w-[50px] h-[3px] mt-2"></div>
                </div>
                <button type="button" id="addToCartBtn"
                    class="add-to-cart-btn bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 md:py-3 md:px-6 rounded-lg"
                    disabled>
                    Add to Cart
                    <i class="fas fa-shopping-cart ml-2"></i>
                </button>
            </div>

            @if ($product['have_product_type'])
                <div
                    class="grid 
                @if (!$product['is_topup']) grid-cols-1 md:grid-cols-2 @endif
                gap-6">
                    @foreach ($product['plans'] as $plan)
                        <div class="plan-card border cursor-pointer rounded-lg p-5 border-gray-200"
                            id="plan-card-{{ $plan['id'] }}">
                            <div class="flex items-start">
                                <input type="radio" name="plan" id="plan_{{ $plan['id'] }}"
                                    value="{{ $plan['id'] }}" class="mt-1 mr-3 plan-radio">
                                <div class="w-full">
                                    <label for="plan_{{ $plan['id'] }}"
                                        class="font-semibold text-gray-800 block text-lg w-max">
                                        {{ $plan['name'] }}
                                    </label>

                                    @if ($plan['description'])
                                        <h6 class="text-sm text-gray-500 my-2">Description :</h6>
                                        <p class="text-sm text-gray-600 mb-2">{{ $plan['description'] }}</p>
                                    @endif

                                    <div class="grid
                    grid-cols-1 sm:grid-cols-2 gap-2 mt-4 duration-container"
                                        id="durations-{{ $plan['id'] }}">
                                        @foreach ($plan['durations'] as $key => $duration)
                                            <div class="duration-option rounded-md p-3 flex justify-between items-center"
                                                data-plan="{{ $plan['id'] }}" data-duration="{{ $duration['id'] }}"
                                                data-price="{{ $duration['price'] }}">
                                                <input type="radio" name="duration"
                                                    id="duration_{{ $plan['id'] }}_{{ $duration['id'] }}"
                                                    value="{{ $duration['id'] }}" class="hidden duration-radio">
                                                <span class="text-sm">
                                                    {{ $duration['duration'] ? $duration['duration'] : 'No Duration.' }}
                                                    @if ($product['is_topup'])
                                                        {{ $duration['unit'] }}
                                                    @endif
                                                </span>
                                                <span
                                                    class="font-semibold text-blue-500">{{ $this->formatPrice($duration['price']) }}
                                                    {{ $duration['notes'] ? "{$duration['notes']}" : '' }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
                    <div class="col-span-full">
                        <h3 class="text-lg font-medium text-gray-700">Select your preference :</h3>
                    </div>
                    @foreach ($product['durations'] as $duration)
                        <div class="duration-option rounded-lg p-4 border flex flex-col justify-between border-gray-200"
                            data-duration="{{ $duration['id'] }}" data-price="{{ $duration['price'] }}">
                            <input type="radio" name="duration" id="duration_{{ $duration['id'] }}"
                                value="{{ $duration['id'] }}" class="hidden duration-radio">
                            <div class="flex justify-between items-center mb-2">
                                <span class="font-medium text-gray-800">{{ $duration['duration'] }}</span>
                            </div>
                            <div class="text-center my-2">
                                <span
                                    class="text-2xl font-bold text-blue-600">{{ $this->formatPrice($duration['price']) }}</span>
                            </div>
                            @if ($duration['notes'])
                                <div class="text-sm text-gray-500 mt-2">{{ $duration['notes'] }}</div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @endif
        </div>

        <!-- Confirmation Modal -->
        <div class="modal-overlay" id="confirmModal">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Confirm Your Order</h3>
                    <p class="modal-subtitle">Please review your selection before adding to cart</p>
                </div>

                <div class="order-summary">
                    <div class="summary-row">
                        <span class="summary-label">Product:</span>
                        <span class="summary-value">{{ $product['app_name'] }}</span>
                    </div>

                    @if ($product['have_product_type'])
                        <div class="summary-row">
                            <span class="summary-label">Plan:</span>
                            <span class="summary-value" id="modalPlan">-</span>
                        </div>
                    @endif


                    <div class="summary-row">
                        <span class="summary-label">{{ $product['is_topup'] ? 'Quantity:' : 'Duration:' }}</span>
                        <span class="summary-value" id="modalDuration">-</span>
                    </div>

                    @if ($product['have_product_type'] && $product['is_topup'])
                        <div class="summary-row">
                            <span class="summary-label">Unit:</span>
                            <span class="summary-value" id="modalUnit">-</span>
                        </div>
                    @endif

                    <div class="summary-row">
                        <span class="summary-label">Total Price:</span>
                        <span class="summary-value total-price" id="modalPrice">Rp 0</span>
                    </div>
                </div>

                <div class="modal-actions">
                    <button type="button" class="btn-secondary" id="cancelBtn">Cancel</button>
                    <button type="button" class="btn-primary" id="confirmBtn">Add to Cart</button>
                </div>
            </div>
        </div>
    @else
        <div class="not-found">
            <div class="not-found-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h3>Product Not Found</h3>
            <p>The product you're looking for doesn't exist or is no longer available.</p>
            <a href="{{ route('catalog') }}" wire:navigate class="btn-link">Browse Products</a>
        </div>
    @endif
</div>

@if ($product)
    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // DOM elements
                const planCards = document.querySelectorAll('.plan-card');
                const durationOptions = document.querySelectorAll('.duration-option');
                const addToCartBtn = document.getElementById('addToCartBtn');

                // Modal elements
                const confirmModal = document.getElementById('confirmModal');
                const modalPlan = document.getElementById('modalPlan');
                const modalDuration = document.getElementById('modalDuration');
                const modalUnit = document.getElementById('modalUnit');
                const modalPrice = document.getElementById('modalPrice');
                const closeConfirm = document.getElementById('cancelBtn');
                const confirmBtn = document.getElementById('confirmBtn');

                // Variables to track selection
                let selectedPlan = null;
                let selectedDuration = null;
                let selectedPrice = 0;
                let selectedDurationText_value = '-';
                let selectedPlanText_value = '-';
                let selectedUnitText_value = '-';
                let hasProductType = {{ $product['have_product_type'] ? 'true' : 'false' }};
                let isTopupProduct = {{ $product['is_topup'] ? 'true' : 'false' }};

                function formatRupiah(price) {
                    return new Intl.NumberFormat('id-ID', {
                        style: 'currency',
                        currency: 'IDR',
                        minimumFractionDigits: 0
                    }).format(price);
                }

                // Get duration text safely from an option element
                function getDurationText(durationOption) {
                    let durationSpan = (!isTopupProduct) ? durationOption.querySelector('span:first-child') :
                        durationOption.querySelector('span.text-sm');

                    if (isTopupProduct) {
                        selectedUnitText_value = durationSpan.textContent.trim().split(/\s+/)[1] || '-';
                    }

                    // For products without types (has different structure)
                    if (!durationSpan || !durationSpan.textContent.trim()) {
                        durationSpan = durationOption.querySelector('.font-medium');
                    }

                    // Fallback if neither is found
                    if (!durationSpan) {
                        // Try to find any span with text content
                        const spans = durationOption.querySelectorAll('span');
                        for (const span of spans) {
                            if (span.textContent.trim() && !span.textContent.includes('Rp')) {
                                durationSpan = span;
                                break;
                            }
                        }
                    }

                    return durationSpan ? durationSpan.textContent.trim() : 'Selected Duration';
                }

                // Update button state
                function updateButtonState() {
                    addToCartBtn.disabled = hasProductType ?
                        !(selectedPlan && selectedDuration) :
                        !selectedDuration;
                }

                function resetAllSelections() {
                    // Reset plan selection
                    planCards.forEach(c => c.classList.remove('selected'));
                    planCards.forEach(c => {
                        const radio = c.querySelector('input[type="radio"]');
                        if (radio) radio.checked = false;
                    });

                    // Reset duration selection
                    durationOptions.forEach(opt => opt.classList.remove('selected'));
                    durationOptions.forEach(opt => {
                        const radio = opt.querySelector('input[type="radio"]');
                        if (radio) radio.checked = false;
                    });

                    // Reset variables
                    selectedPlan = null;
                    selectedDuration = null;
                    selectedPrice = 0;
                    selectedPlanText_value = '-';
                    selectedDurationText_value = '-';
                }

                function selectPlan(planId) {
                    // Find the plan card
                    const planCard = document.getElementById(`plan-card-${planId}`);
                    if (!planCard) return;

                    // Reset all cards' selected state
                    planCards.forEach(c => c.classList.remove('selected'));

                    // Select this card
                    planCard.classList.add('selected');

                    // Check the radio button
                    const radio = planCard.querySelector('input[type="radio"]');
                    radio.checked = true;

                    // Store selected plan
                    selectedPlan = planId;
                    selectedPlanText_value = planCard.querySelector('label').textContent.trim();
                }

                // Handle plan selection
                planCards.forEach(card => {
                    card.addEventListener('click', function() {
                        const radio = card.querySelector('input[type="radio"]');

                        // Check if this plan is already selected (toggle functionality)
                        if (selectedPlan === radio.value) {
                            // Unselect this plan
                            card.classList.remove('selected');
                            radio.checked = false;
                            selectedPlan = null;
                            selectedPlanText_value = '-';

                            // Reset duration selection
                            selectedDuration = null;
                            selectedDurationText_value = '-';
                            durationOptions.forEach(opt => {
                                opt.classList.remove('selected');
                                const durationRadio = opt.querySelector('input[type="radio"]');
                                if (durationRadio) {
                                    durationRadio.checked = false;
                                }
                            });
                        } else {
                            // Reset all cards' selected state
                            planCards.forEach(c => c.classList.remove('selected'));

                            // Select this card
                            card.classList.add('selected');

                            // Check the radio button
                            radio.checked = true;

                            // Store selected plan
                            selectedPlan = radio.value;
                            selectedPlanText_value = card.querySelector('label').textContent.trim();

                            // Reset duration selection
                            selectedDuration = null;
                            selectedDurationText_value = '-';
                            durationOptions.forEach(opt => {
                                opt.classList.remove('selected');
                                const durationRadio = opt.querySelector('input[type="radio"]');
                                if (durationRadio) {
                                    durationRadio.checked = false;
                                }
                            });
                        }

                        // Update button state
                        updateButtonState();
                    });
                });

                durationOptions.forEach(option => {
                    // Make the entire option and all its children clickable
                    const makeClickable = (element) => {
                        element.style.cursor = 'pointer';
                        element.addEventListener('click', function(e) {
                            selectDurationOption(option);
                            e.stopPropagation(); // Prevent event bubbling
                        });

                        // Make all children clickable too
                        Array.from(element.children).forEach(child => {
                            makeClickable(child);
                        });
                    };

                    makeClickable(option);
                });

                function selectDurationOption(option) {
                    const radio = option.querySelector('input[type="radio"]');
                    const planId = option.dataset.plan;

                    // Check if this duration is already selected (toggle functionality)
                    if (selectedDuration === radio.value) {
                        // Unselect this duration
                        option.classList.remove('selected');
                        radio.checked = false;
                        selectedDuration = null;
                        selectedDurationText_value = '-';
                        selectedPrice = 0;
                    } else {
                        if (planId && selectedPlan !== planId) {
                            // Reset all selections first
                            resetAllSelections();

                            // Select the new plan
                            selectPlan(planId);
                        }

                        // Reset all duration options' selected state
                        durationOptions.forEach(opt => {
                            opt.classList.remove('selected');
                            const durationRadio = opt.querySelector('input[type="radio"]');
                            if (durationRadio) {
                                durationRadio.checked = false;
                            }
                        });

                        // Select this option
                        option.classList.add('selected');

                        // Check the radio button
                        radio.checked = true;

                        // Store selected duration and price
                        selectedDuration = radio.value;
                        selectedPrice = parseInt(option.dataset.price);

                        // Get and store the duration text
                        selectedDurationText_value = getDurationText(option);
                    }

                    // Update button state
                    updateButtonState();
                }

                closeConfirm.addEventListener('click', function() {
                    confirmModal.classList.remove('active')
                })

                // Confirm add to cart handler
                confirmBtn.addEventListener('click', function() {
                    // Hide confirmation modal
                    confirmModal.classList.remove('active');

                    // Set the data in Livewire component first
                    Livewire.dispatch('set-cart-data', {
                        detailId: selectedDuration,
                        quantity: 1
                    });

                    // Call Livewire addToCart method
                    Livewire.dispatch('add-to-cart');

                    addToCartBtn.classList.add('success-animation');
                    setTimeout(() => {
                        addToCartBtn.classList.remove('success-animation');
                    }, 600);
                    resetAllSelections();
                    updateButtonState();
                });

                // Initialize any pre-selected options
                const preSelectedPlan = document.querySelector('.plan-card.selected');
                if (preSelectedPlan) {
                    const radio = preSelectedPlan.querySelector('input[type="radio"]');
                    selectedPlan = radio.value;
                    selectedPlanText_value = preSelectedPlan.querySelector('label').textContent.trim();
                }

                const preSelectedDuration = document.querySelector('.duration-option.selected');
                if (preSelectedDuration) {
                    const radio = preSelectedDuration.querySelector('input[type="radio"]');
                    selectedDuration = radio.value;
                    selectedPrice = parseInt(preSelectedDuration.dataset.price);
                    selectedDurationText_value = getDurationText(preSelectedDuration);
                }

                // Update button state on initial load
                updateButtonState();

                // Add to cart button
                addToCartBtn.addEventListener('click', function() {
                    if (this.disabled) return;

                    // Update modal content
                    if (modalPlan && hasProductType) {
                        modalPlan.textContent = selectedPlanText_value;
                    }

                    modalDuration.textContent = (!isTopupProduct) ? selectedDurationText_value :
                        selectedDurationText_value.trim().split(/\s+/)[0];
                    modalPrice.textContent = formatRupiah(selectedPrice);

                    if (modalUnit) modalUnit.textContent = selectedUnitText_value;

                    // Show confirmation modal
                    confirmModal.classList.add('active');
                });
            });
        </script>
    @endpush
@endif
