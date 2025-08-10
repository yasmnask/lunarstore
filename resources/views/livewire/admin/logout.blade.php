<div>
    @if ($type === 'header')
        <a class="dropdown-item btnLogout" href="#"><i class="fas fa-sign-out-alt me-2"></i>
            Logout</a>
    @else
        <a href="#" class="sidebar-link text-danger btnLogout">
            <i class="fas fa-sign-out-alt text-danger"></i>
            <span>Logout</span>
        </a>
    @endif
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            $(document).on('click', '.btnLogout', function(e) {
                console.log('Logout button clicked');
                e.preventDefault();
                Swal.fire({
                    title: 'Are you sure?',
                    text: 'You are about to log out.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#435ebe',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, logout!',
                    reverseButtons: true
                }).then(async result => {
                    if (result.isConfirmed) {
                        Swal.fire({
                            title: 'Logging out...',
                            text: 'Please wait.',
                            allowOutsideClick: false,
                            didOpen: () => Swal.showLoading()
                        });

                        try {
                            await Promise.all([new Promise(resolve => setTimeout(resolve,
                                500)), @this.call('performLogout')]);
                        } catch (error) {
                            console.error('Logout error:', error);
                            Swal.fire('Error!', 'Logout failed. Please try again.', 'error', {
                                confirmButtonColor: '#435ebe'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endpush
