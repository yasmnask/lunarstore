<div>
    <div class="auth-logo d-flex align-items-center gap-3">
        <img src="{{ asset('assets/client/images/logo.png') }}" alt="Logo">
        <h5>LUNAR STORE</h5>
    </div>
    <h1 class="auth-title">Log in.</h1>
    <p class="auth-subtitle mb-3">Log in to Lunar Store Admin Dashboard Panel.</p>

    <x-alert-bootstrap :component="$this" />

    <form wire:submit="login">
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="text" class="form-control form-control-xl @error('username') is-invalid @enderror"
                placeholder="Username" wire:model="username">
            <div class="form-control-icon">
                <i class="bi bi-person"></i>
            </div>
            @error('username')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-group position-relative has-icon-left mb-4">
            <input type="password" class="form-control form-control-xl @error('password') is-invalid @enderror"
                placeholder="Password" wire:model="password">
            <div class="form-control-icon">
                <i class="bi bi-shield-lock"></i>
            </div>
            @error('password')
                <div class="invalid-feedback">
                    {{ $message }}
                </div>
            @enderror
        </div>
        <div class="form-check form-check-lg d-flex align-items-end">
            <input class="form-check-input me-2 c-pointer" wire:model="remember" type="checkbox" id="flexCheckDefault">
            <label class="form-check-label text-gray-600 c-pointer" for="flexCheckDefault">
                Keep me logged in
            </label>
        </div>
        <button type="submit" wire:loading.attr="disabled"
            class="btn btn-primary btn-block btn-lg shadow-lg mt-4 fs-5">

            <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true" wire:loading></span>
            <span wire:loading.remove>Log in <i class="fas fa-sign-in-alt ms-1"></i></span>
            <span wire:loading>Logging in...</span>
        </button>
    </form>
</div>

@push('scripts')
    <script>
        window.addEventListener('show-swal', function(e) {
            setTimeout(() => {
                Swal.fire({
                    title: e.detail[0].title,
                    icon: e.detail[0].icon,
                    text: e.detail[0].text,
                    confirmButtonColor: '#435ebe'
                });
            }, 100);
        });
    </script>
@endpush
