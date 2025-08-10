@props(['component' => null])

@if ($component && $component->showFlash)
    @php
        $classes = [
            'error' => 'bg-red-100 border border-red-400 text-red-700',
            'success' => 'bg-green-100 border border-green-400 text-green-700',
            'info' => 'bg-blue-100 border border-blue-400 text-blue-700',
            'warning' => 'bg-yellow-100 border border-yellow-400 text-yellow-700',
        ];

        $icons_color = [
            'error' => 'text-red-500 hover:text-red-700',
            'success' => 'text-green-500 hover:text-green-700',
            'info' => 'text-blue-500 hover:text-blue-700',
            'warning' => 'text-yellow-500 hover:text-yellow-700',
        ];

        $icons = [
            'error' => 'fas fa-exclamation-circle',
            'success' => 'fas fa-check-circle',
            'info' => 'fas fa-info-circle',
            'warning' => 'fas fa-exclamation-triangle',
        ];
    @endphp

    <div x-data="{
        show: @entangle('showFlash'),
        autoHide: @js($component->autoHide),
        autoHideSeconds: @js($component->autoHideSeconds),
        timeoutId: null,
        remainingTime: @js($component->autoHideSeconds),
        intervalId: null,
    
        init() {
            // Start auto-hide if enabled
            if (this.autoHide && this.autoHideSeconds > 0) {
                this.startAutoHide();
            }
    
            // Watch for changes in autoHide
            this.$watch('autoHide', (value) => {
                if (value && this.autoHideSeconds > 0) {
                    this.startAutoHide();
                } else {
                    this.stopAutoHide();
                }
            });
        },
    
        startAutoHide() {
            this.remainingTime = this.autoHideSeconds;
    
            // Update remaining time every second
            this.intervalId = setInterval(() => {
                this.remainingTime--;
                if (this.remainingTime <= 0) {
                    this.close();
                }
            }, 1000);
    
            // Set timeout to close
            this.timeoutId = setTimeout(() => {
                this.close();
            }, this.autoHideSeconds * 1000);
        },
    
        stopAutoHide() {
            if (this.timeoutId) {
                clearTimeout(this.timeoutId);
                this.timeoutId = null;
            }
            if (this.intervalId) {
                clearInterval(this.intervalId);
                this.intervalId = null;
            }
            this.remainingTime = 0;
        },
    
        close() {
            this.stopAutoHide();
            this.show = false;
            this.$wire.closeFlash();
        }
    }"
        x-effect="if (show) {
        let el = $el;
        while (el && el !== document.body && el.scrollHeight <= el.clientHeight) {
            el = el.parentElement;
        }
        if (el) el.scrollTo({ top: 0, behavior: 'smooth' });
    }"
        x-show="show" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform -translate-y-2 scale-95"
        x-transition:enter-end="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0 scale-100"
        x-transition:leave-end="opacity-0 transform -translate-y-2 scale-95"
        class="{{ $classes[$component->flashType] ?? $classes['error'] }} px-4 py-3 rounded-lg flex items-center justify-between mt-4 shadow-md relative overflow-hidden"
        role="alert">

        <!-- Progress bar for auto-hide -->
        <div x-show="autoHide && autoHideSeconds > 0"
            class="absolute bottom-0 left-0 h-1 bg-current opacity-30 transition-all duration-1000 ease-linear"
            :style="`width: ${(remainingTime / autoHideSeconds) * 100}%`"></div>

        <div class="flex items-center flex-1">
            <i
                class="{{ $icons[$component->flashType] ?? $icons['error'] }} {{ $icons_color[$component->flashType] ?? $icons_color['error'] }} mr-3 mt-0.5 flex-shrink-0"></i>
            <span class="block sm:inline font-medium">{{ $component->flashMessage }}</span>
        </div>

        <div class="flex items-center ml-4 space-x-2">
            <!-- Close button -->
            <button @click="close()" class="focus:outline-none hover:opacity-75 transition-opacity p-1">
                <i class="fas fa-times text-lg {{ $icons_color[$component->flashType] ?? $icons_color['error'] }}"></i>
                <span class="sr-only">Close</span>
            </button>
        </div>
    </div>
@endif
