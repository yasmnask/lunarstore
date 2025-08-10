<div class="w-80 bg-white border-r border-gray-200 flex flex-col">
    <!-- User Profile Header -->
    <div class="p-6 border-b border-gray-200">
        <div class="flex items-center space-x-4">
            <div
                class="w-16 h-16 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center shadow-lg">
                @if ($user['avatar'])
                    <img src="{{ $user['avatar'] }}" alt="User Profile" class="h-full w-full object-cover">
                @else
                    <img src="{{ Avatar::create(auth()->user()->name)->toBase64() }}" alt="User Profile"
                        class="h-full w-full object-cover">
                @endif
            </div>
            <div>
                <h2 class="text-lg font-semibold text-gray-900">{{ $user['name'] }}</h2>
                <p class="text-sm text-gray-600">{{ $user['email'] }}</p>
                <p class="text-xs text-gray-500 mt-1">Member since {{ date('M Y', strtotime($user['created_at'])) }}</p>
            </div>
        </div>
    </div>

    <!-- Navigation -->
    <nav class="flex-1 p-4">
        <div class="space-y-2">
            @include('components.client.profile.nav-item', [
                'section' => 'overview',
                'icon' => 'overview',
                'title' => 'Overview',
                'subtitle' => 'Account summary',
            ])

            @include('components.client.profile.nav-item', [
                'section' => 'personal',
                'icon' => 'user',
                'title' => 'Personal Info',
                'subtitle' => 'Manage your details',
            ])
        </div>
    </nav>

    <!-- Quick Stats -->
    <div class="p-4 border-t border-gray-200">
        <div class="grid grid-cols-2 gap-3">
            <div class="bg-gradient-to-br from-blue-50 to-blue-100 p-3 rounded-xl">
                <p class="text-xs font-medium text-blue-600 uppercase tracking-wide">Orders</p>
                <p class="text-lg font-bold text-blue-900">{{ $stats['total_orders'] }}</p>
            </div>
            <div class="bg-gradient-to-br from-green-50 to-green-100 p-3 rounded-xl">
                <p class="text-xs font-medium text-green-600 uppercase tracking-wide">Spent</p>
                <p class="text-lg font-bold text-green-900">{{ number_format($stats['total_spent'] / 1000) }}K</p>
            </div>
        </div>
    </div>
</div>
