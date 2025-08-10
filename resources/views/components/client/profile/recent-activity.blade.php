<div class="bg-white rounded-2xl border border-gray-200 p-6">
    <h3 class="text-lg font-semibold text-gray-900 mb-6">Recent Activity</h3>
    <div class="space-y-4">
        @include('components.client.profile.activity-item', [
            'icon' => 'fas fa-check',
            'title' => 'Order #ORD-2024-001 completed',
            'subtitle' => 'Premium App Bundle - Rp 299,000',
            'time' => '2 hours ago',
            'color' => 'green',
        ])

        @include('components.client.profile.activity-item', [
            'icon' => 'fas fa-heart',
            'title' => 'Added item to wishlist',
            'subtitle' => 'Mobile Legends Diamond Package',
            'time' => '1 day ago',
            'color' => 'blue',
        ])
    </div>
</div>
