@php
    function is_active($uri)
    {
        return request()->is($uri) ? 'text-blue-600 font-semibold' : 'text-gray-700';
    }
@endphp

<!-- Navigation -->
<nav class="sticky top-0 z-50 bg-white border-b border-gray-100" x-data x-init="$nextTick(() => {
    if (window.location.hash) {
        const el = document.querySelector(window.location.hash);
        if (el) {
            el.scrollIntoView({ behavior: 'smooth' });
        }
    }
});">
    <div class="container px-4 py-2 mx-auto sm:px-6 lg:px-14">
        <div class="flex justify-between h-16">
            <div class="flex items-center">
                <a href="/"
                    @if (!request()->is('/')) wire:navigate @else x-data @click.prevent="
                    window.history.pushState(null, '', '/');
                    window.scrollTo({ top: 0, behavior: 'smooth' });" @endif
                    class="flex items-center gap-3">
                    <img src="{{ asset('assets/client/images/logo.png') }}" alt="Logo Lunar Store" width="45" />
                    <span class="text-blue-600 font-bold text-[18px] lunar-text uppercase">Lunar Store</span>
                </a>
            </div>
            <div class="items-center hidden space-x-8 md:flex mobileMenu">
                @auth
                    <a href="{{ route('catalog') }}" wire:navigate
                        class="{{ is_active('catalog') }} hover:text-blue-600 flex items-center">
                        <i class="fas fa-store mr-1"></i>
                        <span>Catalog</span>
                    </a>
                    <a href="{{ route('cart') }}" wire:navigate
                        class="{{ is_active('cart') }} hover:text-blue-600 inline-flex items-center">
                        <i class="fas fa-shopping-cart mr-1"></i>
                        <span>Cart</span>
                        <livewire:client.cart-counter />
                    </a>
                    <a href="{{ route('orders') }}" wire:navigate
                        class="{{ is_active('orders') }} hover:text-blue-600 flex items-center">
                        <i class="fas fa-history mr-1"></i>
                        <span>Orders</span>
                    </a>

                    <div class="relative" x-data="{ open: false }">
                        <button @click="open = !open"
                            class="flex items-center px-3 py-2 space-x-3 rounded-md hover:bg-gray-100 transition-colors duration-200 w-full"
                            :class="{ 'bg-gray-100': open }">
                            <div class="relative">
                                <div
                                    class="h-10 w-10 rounded-full overflow-hidden bg-gray-200 flex items-center justify-center">
                                    <img src="{{ auth()->user()->getAvatar() }}" alt="User Profile"
                                        class="h-full w-full object-cover">
                                </div>
                                <div
                                    class="absolute -bottom-0 -right-0 h-3 w-3 bg-green-500 rounded-full border-2 border-white">
                                </div>
                            </div>
                            <div class="flex-1 text-left">
                                <span
                                    class="block text-gray-700 font-semibold text-sm">{{ auth()->user()->username && trim(auth()->user()->username) != '_g_' ? auth()->user()->username : 'Guest' }}</span>
                            </div>
                            <i class="fas fa-chevron-down text-gray-400 text-xs transition-transform duration-200"
                                :class="{ 'rotate-180': open }"></i>
                        </button>

                        <div x-show="open" x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                            @click.outside="open = false"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg py-2 z-20 border-gray-200 border">

                            <div class="px-4 py-2 border-b border-gray-100 overflow-hidden" style="word-wrap: break-word;">
                                <p class="text-sm font-medium text-gray-700">{{ auth()->user()->name }}</p>
                                <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                            </div>

                            <a href="{{ auth()->user()->username && trim(auth()->user()->username) != '_g_' ? route('profile', auth()->user()->username) : route('profile', '_g_') }}?tab=overview"
                                wire:navigate
                                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center transition-colors duration-150">
                                <i class="fas fa-user mr-3 w-4 text-gray-400"></i>
                                <span>My Profile</span>
                            </a>

                            <a href="#"
                                class="px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 flex items-center transition-colors duration-150">
                                <i class="fas fa-cog mr-3 w-4 text-gray-400"></i>
                                <span>Settings</span>
                            </a>

                            <hr class="my-1 border-gray-100">

                            <livewire:client.logout />
                        </div>
                    @else
                        <a href="/"
                            @if (!request()->is('/')) wire:navigate @else x-data @click.prevent="
                        window.history.pushState(null, '', '/');
                        window.scrollTo({ top: 0, behavior: 'smooth' });" @endif
                            class="{{ is_active('/') }} transition-colors hover:text-blue-600">Home</a>
                        <a href="{{ route('aboutus') }}"
                            @if (!request()->is('aboutus')) wire:navigate @else x-data @click.prevent="
                        window.history.pushState(null, '', '/aboutus');
                        window.scrollTo({ top: 0, behavior: 'smooth' });" @endif
                            class="{{ is_active('aboutus') }} transition-colors hover:text-blue-600">About Us</a>
                        <a href="/#testimonials" @if (!request()->is('/')) wire:navigate @endif
                            class="text-gray-700 transition-colors hover:text-blue-600">Testimonials</a>
                        <a href="/#pricing" @if (!request()->is('/')) wire:navigate @endif
                            class="text-gray-700 transition-colors hover:text-blue-600">Pricing</a>
                        <a href="/#contact" @if (!request()->is('/')) wire:navigate @endif
                            class="text-gray-700 transition-colors hover:text-blue-600">Contact</a>
                        <a href="{{ route('login') }}" wire:navigate
                            class="px-4 py-3 text-white transition-all bg-blue-500 rounded-md hover:bg-blue-600 active:scale-[0.9]">
                            Login Now <i class="ml-1 fas fa-sign-in-alt"></i>
                        </a>
                    @endauth
                </div>
                <div class="flex items-center md:hidden mobileNav">
                    <button class="text-[24px] text-blue-500">
                        <i class="fas fa-bars"></i>
                    </button>
                </div>
            </div>
        </div>
</nav>
