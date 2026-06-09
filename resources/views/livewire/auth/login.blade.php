@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center py-4 bg-white border-bottom">
            <img src="{{ asset('images/company_logo.png') }}" alt="CRM Technologies Logo" class="img-fluid login-logo-img">
        </div>
        <div class="text-center py-3 bg-white border-bottom">
            <h4 class="mb-1 fw-normal login-title-primary">Laboratory Information</h4>
            <h4 class="mb-0 fw-normal text-secondary login-title-secondary">Management System</h4>
        </div>
        <div class="card-body login-card-body bg-white p-4">
            <form wire:submit.prevent="login">
                @error('email')
                    <div class="alert alert-danger p-2 mb-3 small" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> {{ $message }}
                    </div>
                @enderror

                <div class="input-group mb-3">
                    <div class="input-group-text bg-light">
                        <i class="bi bi-at text-secondary login-input-icon-at"></i>
                    </div>
                    <input type="email" wire:model="email"
                        class="form-control bg-transparent @error('email') is-invalid @enderror" placeholder="Email"
                        required autocomplete="email" autofocus>
                </div>

                <div class="input-group mb-4">
                    <div class="input-group-text bg-light">
                        <i class="bi bi-key-fill text-secondary login-input-icon-key"></i>
                    </div>
                    <input type="password" wire:model="password"
                        class="form-control bg-transparent @error('password') is-invalid @enderror"
                        placeholder="Password" required autocomplete="current-password">
                </div>

                <!--begin::Row-->
                <div class="d-grid mb-3">
                    <button type="submit"
                        class="btn btn-primary py-2 d-flex align-items-center justify-content-center gap-2 fw-normal login-submit-btn"
                        wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="login">Sign In</span>
                        <span wire:loading wire:target="login" class="spinner-border spinner-border-sm" role="status"
                            aria-hidden="true"></span>
                    </button>
                </div>
                <!--end::Row-->
            </form>

            <div class="text-center mt-3">
                <a href="#" class="text-decoration-none text-primary">Forgot Password?</a>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
