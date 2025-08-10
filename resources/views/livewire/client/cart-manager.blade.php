@push('styles')
    <style>
        :root {
            --primary-blue: #2563eb;
            --primary-blue-dark: #1d4ed8;
            --primary-blue-light: #3b82f6;
            --accent-blue: #60a5fa;
            --success-green: #10b981;
            --danger-red: #ef4444;
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
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            box-sizing: border-box;
        }

        .cart-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem 1rem;
            min-height: 70vh;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
        }

        .cart-content {
            display: grid;
            grid-template-columns: 1fr 420px;
            gap: 1.5rem;
            align-items: start;
        }

        .cart-items {
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow-lg);
            border: 1px solid var(--gray-100);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .cart-item {
            padding: 2rem;
            border-bottom: 1px solid var(--gray-100);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
        }

        .cart-item::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            bottom: 0;
            width: 4px;
            background: linear-gradient(135deg, var(--primary-blue), var(--accent-blue));
            transform: scaleY(0);
            transition: transform 0.3s ease;
        }

        .cart-item:hover {
            background: linear-gradient(135deg, #fefefe 0%, #f8fafc 100%);
            transform: translateX(8px);
            box-shadow: var(--shadow-md);
        }

        .cart-item:hover::before {
            transform: scaleY(1);
        }

        .cart-item:last-child {
            border-bottom: none;
        }

        .item-content {
            display: grid;
            grid-template-columns: 100px 1fr auto;
            gap: 1.5rem;
            align-items: center;
        }

        .item-image-wrapper {
            position: relative;
            border-radius: 16px;
            overflow: hidden;
            background: var(--gray-50);
            box-shadow: var(--shadow-md);
        }

        .item-image {
            width: 100px;
            height: 100px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }

        .item-image:hover {
            transform: scale(1.1);
        }

        .item-details h3 {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
            line-height: 1.3;
        }

        .item-meta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
            margin-bottom: 0.75rem;
        }

        .meta-badge {
            background: var(--gray-100);
            color: var(--gray-700);
            padding: 0.375rem 0.75rem;
            border-radius: 50px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.025em;
            transition: all 0.2s ease;
        }

        .meta-badge:hover {
            transform: translateY(-1px);
            box-shadow: var(--shadow-sm);
        }

        .meta-badge.plan {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-light));
            color: white;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }

        .meta-badge.category {
            background: linear-gradient(135deg, var(--success-green), #059669);
            color: white;
            box-shadow: 0 4px 6px -1px rgba(16, 185, 129, 0.3);
        }

        .item-price {
            font-size: 1.125rem;
            font-weight: 700;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .item-subtotal {
            font-size: 0.875rem;
            color: var(--gray-500);
            margin-top: 0.25rem;
        }

        .item-actions {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 1rem;
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            background: var(--gray-50);
            border-radius: 12px;
            padding: 0.5rem;
            border: 1px solid var(--gray-200);
            box-shadow: var(--shadow-sm);
        }

        .qty-btn {
            width: 36px;
            height: 36px;
            border: none;
            background: white;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
            color: var(--gray-600);
            box-shadow: var(--shadow-sm);
            font-size: 0.875rem;
        }

        .qty-btn:hover:not(:disabled) {
            background: var(--primary-blue);
            color: white;
            transform: scale(1.1);
            box-shadow: 0 4px 8px -2px rgba(37, 99, 235, 0.4);
        }

        .qty-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
        }

        .qty-input {
            width: 60px;
            text-align: center;
            border: none;
            background: transparent;
            font-weight: 700;
            color: var(--gray-900);
            font-size: 1rem;
        }

        .remove-btn {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.1), rgba(239, 68, 68, 0.05));
            border: 1px solid rgba(239, 68, 68, 0.2);
            color: var(--danger-red);
            cursor: pointer;
            padding: 0.5rem 1rem;
            border-radius: 8px;
            transition: all 0.2s ease;
            font-size: 0.875rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .remove-btn:hover {
            background: var(--danger-red);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 4px 8px -2px rgba(239, 68, 68, 0.4);
        }

        .cart-summary {
            background: white;
            border-radius: 15px;
            padding: 2.5rem;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--gray-100);
            height: fit-content;
            position: sticky;
            top: 2rem;
            backdrop-filter: blur(10px);
        }

        .summary-title {
            font-size: 1.75rem;
            font-weight: 600;
            color: var(--gray-800);
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid var(--gray-100);
            position: relative;
        }

        .summary-title::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 60px;
            height: 2px;
            background: linear-gradient(90deg, var(--primary-blue), var(--accent-blue));
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.25rem;
            padding: 0.75rem 0;
            transition: all 0.2s ease;
        }

        .summary-row:hover {
            background: var(--gray-50);
            margin: 0 -1rem 1.25rem -1rem;
            padding: 0.75rem 1rem;
            border-radius: 8px;
        }

        .summary-row:last-of-type {
            margin-bottom: 2rem;
            padding: 1.5rem 0;
            border-top: 2px solid var(--gray-200);
            font-weight: 700;
            font-size: 1.25rem;
            background: linear-gradient(135deg, #f8fafc, #e2e8f0);
            margin: 0 -1rem 2rem -1rem;
            padding: 1.5rem 1rem;
            border-radius: 12px;
        }

        .summary-label {
            color: var(--gray-600);
            font-weight: 500;
        }

        .summary-value {
            font-weight: 600;
            color: var(--gray-900);
        }

        .total-value {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-size: 1.5rem;
            font-weight: 800;
        }

        .checkout-btn {
            width: 100%;
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            color: white;
            border: none;
            padding: 1.25rem;
            border-radius: 16px;
            font-weight: 700;
            font-size: 1.125rem;
            cursor: pointer;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            margin-bottom: 1rem;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
            position: relative;
            overflow: hidden;
        }

        .checkout-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }

        .checkout-btn:hover:not(:disabled) {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(37, 99, 235, 0.4);
        }

        .checkout-btn:hover::before {
            left: 100%;
        }

        .checkout-btn:disabled {
            background: var(--gray-400);
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .clear-btn {
            width: 100%;
            background: white;
            color: var(--danger-red);
            border: 2px solid var(--danger-red);
            padding: 1rem;
            border-radius: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .clear-btn:hover {
            background: var(--danger-red);
            color: white;
            transform: translateY(-1px);
            box-shadow: 0 8px 15px -3px rgba(239, 68, 68, 0.4);
        }

        .empty-cart {
            text-align: center;
            padding: 6rem 2rem;
            background: white;
            border-radius: 15px;
            box-shadow: var(--shadow-xl);
            border: 1px solid var(--gray-100);
        }

        .empty-icon {
            font-size: 5rem;
            color: var(--gray-300);
            animation: float 3s ease-in-out infinite;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .empty-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--color-gray-60000);
            margin-bottom: 1rem;
        }

        .empty-text {
            color: var(--gray-600);
            margin-bottom: 25px;
            font-size: 1.125rem;
            line-height: 1.6;
        }

        .shop-btn {
            background: linear-gradient(135deg, var(--primary-blue), var(--primary-blue-dark));
            color: white;
            padding: 1rem 2rem;
            border-radius: 12px;
            text-decoration: none;
            font-weight: 700;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 1.125rem;
            box-shadow: 0 4px 6px -1px rgba(37, 99, 235, 0.3);
        }

        .shop-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -5px rgba(37, 99, 235, 0.4);
        }

        /* Animation States */
        .fade-out {
            animation: fadeOut 0.3s ease forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateX(-20px);
            }
        }

        .success-flash {
            animation: successFlash 0.8s ease;
        }

        @keyframes successFlash {

            0%,
            100% {
                background: white;
                transform: scale(1);
            }

            50% {
                background: rgba(16, 185, 129, 0.1);
                transform: scale(1.02);
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.7;
            }
        }

        /* Responsive Design */
        @media (max-width: 1024px) {
            .cart-content {
                grid-template-columns: 1fr 350px;
                gap: 2rem;
            }
        }

        @media (max-width: 768px) {
            .cart-container {
                padding: 1rem;
            }

            .cart-content {
                grid-template-columns: 1fr;
                gap: 1.5rem;
            }

            .item-content {
                grid-template-columns: 80px 1fr;
                gap: 1rem;
            }

            .item-actions {
                grid-column: 1 / -1;
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                margin-top: 1.5rem;
                padding-top: 1.5rem;
                border-top: 1px solid var(--gray-100);
            }

            .item-image-wrapper,
            .item-image {
                width: 80px;
                height: 80px;
            }

            .cart-summary {
                position: static;
            }
        }

        @media (max-width: 480px) {
            .item-content {
                grid-template-columns: 1fr;
                text-align: center;
            }

            .item-actions {
                justify-content: center;
                flex-direction: column;
                gap: 1rem;
            }
        }

        /* Focus states for accessibility */
        .qty-btn:focus,
        .remove-btn:focus,
        .checkout-btn:focus,
        .clear-btn:focus,
        .shop-btn:focus {
            outline: 2px solid var(--primary-blue);
            outline-offset: 2px;
        }
    </style>
@endpush

<div class="cart-container">
    <div class="bg-white shadow-sm mb-5 rounded-lg">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14 py-8">
            <h1 class="lunar-text text-2xl md:text-3xl font-bold text-blue-500">SHOPPING CART</h1>
            <p class="text-gray-600 text-xl mt-2">
                @if (count($cartItems) > 0)
                    You have {{ count($cartItems) }} item{{ count($cartItems) > 1 ? 's' : '' }} in your cart
                @else
                    Your cart is empty
                @endif
            </p>
        </div>
    </div>
    {{-- @php
        dd($cartSummary);
    @endphp --}}

    @if (count($cartItems) > 0)
        <div class="cart-content">
            <!-- Cart Items -->
            <div class="cart-items">
                @foreach ($cartItems as $item)
                    <div class="cart-item" wire:key="cart-item-{{ $item['id'] }}">
                        <div class="item-content">
                            <div class="item-image-wrapper">
                                @if ($item['product']['cover_img'])
                                    <img src="{{ $item['product']['cover_img'] }}" alt="{{ $item['product']['name'] }}"
                                        class="item-image"
                                        onerror="this.src='https://placehold.co/100x100?text={{ $item['product']['name'] }}'">
                                @else
                                    <img src="https://placehold.co/100x100?text={{ $item['product']['name'] }}"
                                        alt="{{ $item['product']['name'] }}" class="item-image">
                                @endif
                            </div>

                            <div class="item-details">
                                <h3>{{ $item['product']['name'] }}</h3>
                                <div class="item-meta">
                                    <span class="meta-badge category">{{ $item['product']['category']['title'] }}</span>
                                    @if ($item['plan'])
                                        <span class="meta-badge plan">{{ $item['plan']['name'] }}</span>
                                    @endif
                                    <span class="meta-badge">{{ $item['duration'] ?: 'No Duration' }}</span>
                                </div>
                                <div class="item-price">{{ $item['formatted_price'] }}</div>
                                <div class="item-subtotal">Subtotal: {{ $item['formatted_subtotal'] }}</div>
                                @if ($item['notes'])
                                    <p class="text-sm text-gray-500 mt-1">{{ $item['notes'] }}</p>
                                @endif
                            </div>

                            <div class="item-actions">
                                <div class="quantity-controls">
                                    <button class="qty-btn"
                                        wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] - 1 }})"
                                        {{ $item['quantity'] <= 1 ? 'disabled' : '' }} aria-label="Decrease quantity">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                    <input type="number" class="qty-input" value="{{ $item['quantity'] }}"
                                        min="1"
                                        wire:change="updateQuantity({{ $item['id'] }}, $event.target.value)"
                                        aria-label="Quantity">
                                    <button class="qty-btn"
                                        wire:click="updateQuantity({{ $item['id'] }}, {{ $item['quantity'] + 1 }})"
                                        aria-label="Increase quantity">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                                <button class="remove-btn" wire:click="removeItem({{ $item['id'] }})"
                                    wire:confirm="Are you sure you want to remove this item?"
                                    aria-label="Remove item from cart">
                                    <i class="fas fa-trash"></i>
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Cart Summary -->
            <div class="cart-summary">
                <h3 class="summary-title">Order Summary</h3>

                <div class="summary-row">
                    <span class="summary-label">Items ({{ $cartSummary['count'] }}):</span>
                    <span class="summary-value">{{ $cartSummary['formatted_subtotal'] }}</span>
                </div>

                <div class="summary-row">
                    <span class="summary-label">Total:</span>
                    <span class="summary-value total-value">{{ $cartSummary['formatted_total'] }}</span>
                </div>

                <button class="checkout-btn" wire:click="proceedToCheckout">
                    <i class="fas fa-credit-card mr-2"></i>
                    Proceed to Checkout
                </button>

                <button class="clear-btn" wire:click="clearCart"
                    wire:confirm="Are you sure you want to clear your entire cart?">
                    <i class="fas fa-trash mr-2"></i>
                    Clear Cart
                </button>
            </div>
        </div>
    @else
        <!-- Empty Cart -->
        <div class="empty-cart">
            <div class="empty-icon">
                <i class="fas fa-shopping-cart"></i>
            </div>
            <h3 class="empty-title">Your cart is empty</h3>
            <p class="empty-text">Looks like you haven't added any items to your cart yet. Start exploring our amazing
                products!</p>
            <a href="{{ route('catalog') }}" class="shop-btn">
                <i class="fas fa-store"></i>
                Start Shopping
            </a>
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('livewire:initialized', () => {
            // Enhanced animations and interactions
            Livewire.on('cart-updated', () => {
                const cartItems = document.querySelectorAll('.cart-item');
                cartItems.forEach((item, index) => {
                    setTimeout(() => {
                        item.classList.add('success-flash');
                        setTimeout(() => {
                            item.classList.remove('success-flash');
                        }, 800);
                    }, index * 100);
                });
            });

            // Smooth scroll to top when cart updates
            Livewire.on('cart-cleared', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endpush
