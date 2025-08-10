<div class="bg-white rounded-2xl p-6 border border-gray-200 hover:shadow-lg transition-shadow">
    <div class="flex items-center justify-between mb-4">
        <div class="w-12 h-12 bg-{{ $color }}-100 rounded-xl flex items-center justify-center">
            @include('components.client.icons.' . $icon, ['class' => 'w-6 h-6 text-' . $color . '-600'])
        </div>
    </div>
    <p class="text-2xl font-bold text-gray-900 mb-1">{{ $value }}</p>
    <p class="text-sm text-gray-600">{{ $label }}</p>
</div>
