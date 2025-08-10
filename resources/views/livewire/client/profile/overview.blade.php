<div class="p-8">
    <div class="max-w-4xl">
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Account Overview</h1>
            <p class="text-gray-600">Welcome back! Here's what's happening with your account.</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            @include('components.client.profile.overview-stat', [
                'icon' => 'shopping-bag',
                'value' => $stats['total_orders'],
                'label' => 'Total Orders',
                'color' => 'blue',
            ])

            @include('components.client.profile.overview-stat', [
                'icon' => 'currency',
                'value' => 'Rp ' . number_format($stats['total_spent']),
                'label' => 'Total Spent',
                'color' => 'green',
            ])

            @include('components.client.profile.overview-stat', [
                'icon' => 'badge',
                'value' => $stats['active_subscriptions'],
                'label' => 'Active Subscriptions',
                'color' => 'purple',
            ])

            {{-- @include('components.client.profile.overview-stat', [
                'icon' => 'heart',
                'value' => $stats['wishlist_items'],
                'label' => 'Wishlist Items',
                'color' => 'red',
            ]) --}}
        </div>

        <!-- Recent Activity -->
        @include('components.client.profile.recent-activity')
    </div>
</div>
