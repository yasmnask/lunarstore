<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ 'Lunar Store Admin - ' . $title ?? config('app.name') }}</title>
    <link rel="shortcut icon" href="{{ asset('assets/client/images/logo.png') }}" type="image/x-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&family=Playfair+Display:wght@400;700&family=Righteous&display=swap"
        rel="stylesheet">

    @livewireStyles
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/compiled/css/auth.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/admin/custom.css') }}">

    <link
        href="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.2/af-2.7.0/b-3.2.3/b-colvis-3.2.3/b-print-3.2.3/cr-2.1.1/cc-1.0.4/date-1.5.5/fc-5.0.4/fh-4.0.2/kt-2.12.1/r-3.0.4/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.2/sp-2.3.3/sl-3.0.1/sr-1.4.1/datatables.min.css"
        rel="stylesheet" integrity="sha384-TlPrW7HQQtafad6WPrydBWlAc0UnSI9ye7clOBvRvSO7MUIjejkfQPrOLP6iRg7v"
        crossorigin="anonymous">
    @stack('styles')
</head>

<body>
    <script defer src="{{ asset('assets/admin/static/js/initTheme.js') }}"></script>
    @php
        $isAuthPage = request()->is('admin/login');
    @endphp

    @if ($isAuthPage)
        <div id="auth" class="vh-100 overflow-hidden">
            <div class="row h-100 m-0">
                <!-- LEFT -->
                <div class="col-lg-5 col-12 px-4 py-2 overflow-auto" style="max-height: 100vh;">
                    <div id="auth-left">
                        {{ $slot ?? '' }}
                    </div>
                </div>

                <!-- RIGHT -->
                <div class="col-lg-7 d-none d-lg-block position-fixed top-0 end-0 h-100 p-0">
                    <div id="auth-right" class="w-100 h-100">
                        <img src="{{ asset('assets/client/images/bg_login_lunar2.png') }}" alt="Illustration"
                            class="img-fluid h-100 w-100 object-fit-cover" />
                    </div>
                </div>
            </div>
        </div>
    @else
        <div id="app">
            <x-admin.sidebar />
            <div id="main" class="layout-navbar navbar-fixed">
                <x-admin.header />

                <div id="main-content">
                    {{ $slot ?? '' }}
                </div>

                <x-admin.footer />
            </div>
        </div>
    @endif

    <script src="{{ asset('assets/admin/compiled/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    @livewireScripts
    @vite('resources/js/admin.js')
    @if (!$isAuthPage)
        {{-- Admin Page JS --}}
        <script src=" {{ asset('assets/admin/static/js/components/dark.js') }}"></script>
        <script src="{{ asset('assets/admin/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>

        <!-- Include Chart.js -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
        <script
            src="https://cdn.datatables.net/v/bs5/jq-3.7.0/dt-2.3.2/af-2.7.0/b-3.2.3/b-colvis-3.2.3/b-print-3.2.3/cr-2.1.1/cc-1.0.4/date-1.5.5/fc-5.0.4/fh-4.0.2/kt-2.12.1/r-3.0.4/rg-1.5.1/rr-1.5.0/sc-2.4.3/sb-1.8.2/sp-2.3.3/sl-3.0.1/sr-1.4.1/datatables.min.js"
            integrity="sha384-T5JsoPWbI1k4R8QhFo0pwD4XPUg6raKMhvMIkr+WOg4Jx3EujydY4gKNvw4MzfJT" crossorigin="anonymous">
        </script>
    @endif
    <script>
        document.addEventListener('admin-updated', (data) => {
            const nameElement = document.getElementById('admin-fullname');
            const usernameElement = document.getElementById('admin-username');

            if (nameElement) {
                nameElement.textContent = data.detail[0].full_name;
            }
            if (usernameElement) {
                usernameElement.textContent = "Hello, " + data.detail[0].username;
            }
        });
    </script>
    @stack('scripts')
</body>

</html>
