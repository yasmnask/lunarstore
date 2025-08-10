<button
    @click="$wire.setActiveSection('{{ $section }}')

    const url = new URL(window.location.href);
    url.searchParams.set('tab', '{{ $section }}');
    history.replaceState(null, '', url);
    "
    :class="activeSection === '{{ $section }}' ? 'bg-blue-50 text-blue-700 border-blue-200' :
        'text-gray-700 hover:bg-gray-50 border-transparent'"
    class="w-full text-left px-4 py-3 rounded-xl border transition-all duration-200 flex items-center space-x-3 group">
    <div class="w-8 h-8 rounded-lg bg-gray-100 group-hover:bg-gray-200 flex items-center justify-center transition-colors"
        :class="activeSection === '{{ $section }}' ? 'bg-blue-100' : ''">
        @include('components.client.icons.' . $icon, ['class' => 'w-4 h-4'])
    </div>
    <div>
        <p class="font-medium">{{ $title }}</p>
        <p class="text-xs text-gray-500">{{ $subtitle }}</p>
    </div>
</button>
