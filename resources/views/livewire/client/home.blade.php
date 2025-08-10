<div>
    <!-- Hero Section -->
    <section class="relative py-20 text-white overflow-x-clip bg-gradient-to-r from-blue-500 to-blue-800">
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute left-0 right-0 top-0 bg-white/10 h-[1px]"></div>
            <div class="absolute left-0 right-0 bottom-0 bg-white/5 h-[1px]"></div>
            <div class="absolute rounded-full -left-40 -top-40 h-80 w-80 bg-white/10 blur-3xl"></div>
            <div class="absolute rounded-full -right-40 -bottom-40 h-80 w-80 bg-white/10 blur-3xl"></div>
        </div>
        <div class="container relative px-4 mx-auto max-w-7xl sm:px-6 lg:px-14">
            <div class="lg:flex lg:items-center lg:justify-between">
                <div class="lg:w-1/2">
                    <h1 class="mb-8 text-4xl font-bold md:text-5xl lg:text-6xl">
                        Premium Digital Products
                    </h1>
                    <p class="mb-6 text-xl text-white">
                        Get premium apps and game top-ups at the best prices
                    </p>
                    <div class="flex flex-col gap-4 sm:flex-row">
                        <a href="#featured"
                            class="px-6 py-3 text-lg font-medium text-blue-500 transition-all bg-white rounded-md active:scale-[0.9] hover:bg-blue-50">
                            Browse Products <i class="ml-1 text-sm fas fa-angle-double-right"></i>
                        </a>
                    </div>
                </div>
                <div class="mt-10 lg:mt-0 lg:w-1/2">
                    <div class="relative h-64 sm:h-72 md:h-80 lg:h-96">
                        <!--This is moon animation-->
                        <div class="transform -translate-x-40 -translate-y-80">
                            <dotlottie-player
                                src="https://lottie.host/07e69a38-119f-4261-a8e5-326f33bb2158/RY7A2B3V50.lottie"
                                background="transparent" speed="1" style="width: 1150px; height: 1150px" loop
                                autoplay>
                            </dotlottie-player>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="py-12 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14">
            <h2 class="text-3xl font-bold text-playfair text-blue-600">Categories</h2>
            <div class="bg-blue-600 w-[50px] h-[3px] mb-8 mt-2"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach ($categories as $category)
                    <a href="/catalog?category={{ $category->slug }}" wire:navigate class="group h-full">
                        <div
                            class="bg-blue-50 rounded-lg overflow-hidden shadow-md transition-transform group-hover:scale-105 h-full flex flex-col">
                            <div class="h-48 relative">
                                @if ($category->image)
                                    <img src="{{ $category->image }}" alt="{{ $category->title }}"
                                        class="object-cover w-full h-full"
                                        onerror="this.src='https://placehold.co/300x200?text={{ $category->title }}'">
                                @else
                                    <img src="https://placehold.co/300x200?text={{ $category->title }}"
                                        alt="{{ $category->title }}" class="object-cover w-full h-full">
                                @endif
                            </div>
                            <div class="p-4 flex-1 flex flex-col">
                                <h3 class="text-lg font-medium text-gray-900">{{ $category->title }}</h3>
                                <p class="text-sm text-gray-600 mt-3">{{ $category->description }}</p>
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="py-12 bg-gradient-to-b from-white to-blue-50" id="featured">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-14">
            <h2 class="text-3xl font-bold text-playfair text-blue-600">Featured Products</h2>
            <div class="bg-blue-600 w-[50px] h-[3px] mb-8 mt-2"></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach ($featuredProducts as $product)
                    <div
                        class="bg-white rounded-lg overflow-hidden shadow-md hover:shadow-lg transition-shadow flex flex-col h-[400px] relative">
                        <div class="h-48 relative">
                            @if ($product->cover_img)
                                <img src="{{ $product->cover_img }}" alt="{{ $product->app_name }}"
                                    class="object-cover w-full h-full"
                                    onerror="this.src='https://placehold.co/300x200?text={{ $product->app_name }}'">
                            @else
                                <img src="https://placehold.co/300x200?text={{ $product->app_name }}"
                                    alt="{{ $product->app_name }}" class="object-cover w-full h-full">
                            @endif
                        </div>
                        <div class="p-6 pb-24">
                            <h3 class="text-lg font-semibold text-gray-900">{{ $product->app_name }}</h3>
                            <p class="text-sm text-gray-600 mt-1 overflow-hidden line-clamp-3">
                                {{ Str::limit($product->description, 150) }}
                            </p>
                        </div>

                        <div class="absolute bottom-0 left-0 right-0 p-6 flex items-center justify-between">
                            <div class="flex flex-col items-start">
                                <span class="text-blue-500 font-medium text-sm">Starting From</span>
                                <span class="text-lg font-semibold text-blue-500">
                                    {{ $this->formatPrice($product->starting_price) }}
                                </span>
                            </div>
                            <a href="{{ route('product.details', $product->id) }}" wire:navigate
                                class="bg-blue-500 text-white px-4 py-2 rounded-md text-sm hover:bg-blue-600 transition-all active:scale-[0.9]">
                                <i class="fas fa-eye mr-2"></i>View Details
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('catalog') }}" wire:navigate
                    class="inline-block border border-blue-500 text-blue-500 px-6 py-3 rounded-md font-medium hover:bg-blue-500 hover:text-white transition-all active:scale-[0.9]">
                    View All Products <i class="ml-1 text-sm fas fa-angle-double-right"></i>
                </a>
            </div>
        </div>
    </section>
</div>

@push('scripts')
    <script>
        window.addEventListener('show-swal', function(e) {
            // Small delay to ensure page is fully loaded
            setTimeout(() => {
                Swal.fire({
                    title: e.detail[0].title,
                    icon: e.detail[0].icon,
                    text: e.detail[0].text,
                    confirmButtonColor: '#435ebe'
                });
            }, 100);
        });
    </script>
    <script src="https://unpkg.com/@dotlottie/player-component@2.7.12/dist/dotlottie-player.mjs" type="module"></script>
@endpush
