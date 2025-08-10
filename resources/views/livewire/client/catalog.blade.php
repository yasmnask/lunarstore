@push('styles')
    <style>
        .out_of_stock {
            opacity: 0.6;
            cursor: not-allowed;
        }

        .disabled-btn-view {
            pointer-events: none;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.8);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 10;
        }
    </style>
@endpush

<div>
    <!-- Catalog Header -->
    <div class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14 py-6">
            <h1 class="lunar-text text-2xl md:text-3xl font-bold text-blue-500">PRODUCT CATALOG</h1>
            <p class="text-gray-600 mt-2">Browse our collection of premium digital products.</p>
        </div>
    </div>

    <!-- Category Filter -->
    <div class="bg-white border-t border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14 py-4">
            <div class="flex flex-wrap items-center gap-3">
                <button wire:click="filterByCategory('')"
                    class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $selectedCategory == '' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}"
                    wire:loading.attr="disabled">
                    All Products
                </button>
                @foreach ($categories as $category)
                    <button wire:click="filterByCategory('{{ $category->slug }}')"
                        class="px-4 py-2 rounded-md text-sm font-medium transition-colors {{ $selectedCategory == $category->slug ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-800 hover:bg-gray-200' }}"
                        wire:loading.attr="disabled">
                        {{ $category->title }}
                    </button>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Product Catalog -->
    <section class="py-12 bg-gradient-to-b from-blue-50 to-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14">
            <div class="flex justify-between items-center mb-8">
                <div class="page-title">
                    <h2 class="text-xl md:text-2xl font-bold text-blue-600 text-playfair">{{ $categoryTitle }}</h2>
                    <div class="bg-blue-600 w-[50px] h-[3px] mt-2"></div>
                </div>

                <!-- Pagination Info -->
                @if ($products->hasPages())
                    <div class="text-sm text-gray-600">
                        Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }}
                        results
                    </div>
                @endif
            </div>

            <!-- Products Grid -->
            <div class="relative">
                <!-- Loading Overlay -->
                <div wire:loading.flex wire:target="filterByCategory,gotoPage,nextPage,previousPage"
                    class="loading-overlay">
                    <div class="text-center">
                        <i class="fas fa-spinner fa-spin text-3xl text-blue-500 mb-2"></i>
                        <p class="text-gray-600">Loading products...</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6"
                    wire:loading.class="opacity-50">
                    @forelse ($products as $product)
                        <div
                            class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col h-[440px] relative {{ !$product->ready_stock ? 'out_of_stock' : '' }}">
                            <div class="h-48 relative">
                                @if ($product->cover_img)
                                    <img src="{{ $product->cover_img }}" alt="{{ $product->app_name }}"
                                        class="object-cover w-full h-full"
                                        onerror="this.src='https://placehold.co/200x300?text={{ $product->app_name }}'">
                                @else
                                    <img src="https://placehold.co/200x300?text={{ $product->app_name }}"
                                        alt="{{ $product->app_name }}" class="object-cover w-full h-full">
                                @endif
                            </div>
                            <div class="p-6 pb-24">
                                <h3 class="text-lg font-semibold text-gray-900">{{ $product->app_name }}</h3>
                                <p class="text-sm text-gray-600 mt-1 overflow-hidden line-clamp-3">
                                    {{ $this->truncateDescription($product->description) }}
                                </p>
                            </div>

                            <!-- Absolutely positioned price and button row -->
                            <div class="absolute bottom-0 left-0 right-0 p-6">
                                <div class="flex flex-col items-start mb-2">
                                    <span class="text-blue-500 font-medium text-sm">Starting From</span>
                                    <span class="text-lg font-semibold text-blue-500">
                                        {{ $this->formatPrice($product->starting_price) }}
                                    </span>
                                </div>
                                @if ($product->ready_stock)
                                    <a href="{{ route('product.details', $product->id) }}"
                                        class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm block text-center hover:bg-blue-600 transition-all active:scale-[0.9]">
                                        <i class="fas fa-eye mr-2"></i>View Details
                                    </a>
                                @else
                                    <div
                                        class="bg-red-100 text-red-500 px-4 py-2 rounded-md text-sm block text-center disabled-btn-view">
                                        <i class="fas fa-ban mr-2"></i>Out of Stock
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center py-12">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-box-open text-6xl"></i>
                            </div>
                            <h3 class="text-lg font-medium text-gray-900 mb-2">No products found</h3>
                            <p class="text-gray-600">Try selecting a different category or check back later.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Pagination -->
            @if ($products->hasPages())
                <div class="mt-12 flex justify-center">
                    {{ $products->links('livewire.client.catalog-pagination') }}
                </div>
            @endif
        </div>
    </section>
</div>

@push('scripts')
    <script>
        // Smooth scroll to top when pagination changes
        document.addEventListener('livewire:init', () => {
            Livewire.on('gotoPage', () => {
                window.scrollTo({
                    top: 0,
                    behavior: 'smooth'
                });
            });
        });
    </script>
@endpush
