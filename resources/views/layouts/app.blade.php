<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'Lunar Store - ' . $title ?? config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/client/images/logo.png') }}" type="image/x-icon">
    @stack('head')
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&family=Righteous&display=swap"
        rel="stylesheet">

    @vite('resources/css/app.css')

    @livewireStyles
    <link rel="stylesheet" href="{{ asset('assets/client/css/custom.css') }}" />

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    @stack('styles')
</head>

<body class="min-h-screen bg-white">
    @php
        $isAuthPage = request()->is('login') || request()->is('register');
    @endphp

    @if (!$isAuthPage)
        <x-client.nav-menu />
    @endif

    {{-- Main Content --}}
    {{ $slot ?? '' }}

    @if (!$isAuthPage)
        <x-client.footer />
    @endif

    {{-- JS --}}
    @vite('resources/js/app.js')
    @livewireScripts
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const mobileMenuButton = document.querySelector(".mobileNav button");
            const mobileMenuButtonIcon = document.querySelector(".mobileNav button > i");
            const mobileMenu = document.querySelector(
                ".mobileMenu"
            );

            if (mobileMenuButton && mobileMenu) {
                mobileMenuButton.addEventListener("click", function() {
                    if (mobileMenu.classList.contains("hidden")) {
                        mobileMenuButtonIcon.classList.remove("fa-bars");
                        mobileMenuButtonIcon.classList.add("fa-times");
                        mobileMenu.classList.remove("hidden", "space-x-8");
                        mobileMenu.classList.add(
                            "flex",
                            "flex-col",
                            "absolute",
                            "top-16",
                            "left-0",
                            "right-0",
                            "bg-white",
                            "px-4",
                            "py-6",
                            "shadow-md",
                            "z-50",
                            "space-y-6"
                        );
                    } else {
                        mobileMenuButtonIcon.classList.remove("fa-times");
                        mobileMenuButtonIcon.classList.add("fa-bars");
                        mobileMenu.classList.add("hidden");
                        mobileMenu.classList.remove(
                            "flex",
                            "flex-col",
                            "absolute",
                            "top-16",
                            "left-0",
                            "right-0",
                            "bg-white",
                            "p-4",
                            "shadow-md",
                            "z-50",
                            "space-y-4"
                        );
                    }
                });
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
