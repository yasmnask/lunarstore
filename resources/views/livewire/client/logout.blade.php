<a href="#" wire:click.prevent="logout"
    class="px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 flex items-center transition-colors duration-150">
    <i class="fas fa-sign-out-alt mr-3 w-4"></i>
    <span>Sign Out</span>
</a>

@push('scripts')
    <script>
        window.addEventListener('swal-confirm', function(e) {
            Swal.fire({
                title: e.detail[0].title,
                text: e.detail[0].text,
                icon: e.detail[0].icon,
                showCancelButton: true,
                confirmButtonColor: e.detail[0].confirmButtonColor,
                cancelButtonColor: e.detail[0].cancelButtonColor,
                confirmButtonText: e.detail[0].confirmButtonText,
                reverseButtons: true,
                allowOutsideClick: false
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call(e.detail[0].action);
                }
            });
        });
    </script>
@endpush
