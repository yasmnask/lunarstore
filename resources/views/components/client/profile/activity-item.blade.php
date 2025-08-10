<div class="flex items-center space-x-4 p-4 bg-gray-50 rounded-xl">
    <div class="w-10 h-10 bg-{{ $color }}-100 rounded-full flex items-center justify-center">
        <i class="{{ $icon }} text-{{ $color }}-600 text-lg"></i>
    </div>
    <div class="flex-1">
        <p class="font-medium text-gray-900">{{ $title }}</p>
        <p class="text-sm text-gray-600">{{ $subtitle }}</p>
        <p class="text-xs text-gray-500">{{ $time }}</p>
    </div>
</div>
