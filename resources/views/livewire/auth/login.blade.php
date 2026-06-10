@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<div class="login-box">
    <div class="card card-outline card-primary">
        <div class="card-header text-center py-4 bg-white border-bottom">
            <img src="{{ asset('images/company_logo.png') }}" alt="Company Logo" class="img-fluid login-logo-img">
        </div>
        <div class="text-center py-3 bg-white border-bottom">
            <h4 class="mb-1 fw-normal login-title-primary">Laboratory Information</h4>
            <h4 class="mb-0 fw-normal text-secondary login-title-secondary">Management System</h4>
        </div>
        <div class="card-body login-card-body bg-white p-4">
            <form wire:submit.prevent="login">
                @if ($errorMessage)
                    <div class="alert alert-danger alert-dismissible bg-danger text-white border-0 p-3 mb-3 position-relative rounded shadow-sm"
                        role="alert">
                        <div class="d-flex align-items-center gap-2 mb-1">
                            <h5 class="text-white"><i class="bi bi-ban text-white fs-5 me-2"></i> Error!</h5>
                        </div>
                        <div class="small">{{ $errorMessage }}</div>
                        <button type="button" wire:click="$set('errorMessage', '')"
                            class="btn-close btn-close-white position-absolute top-0 end-0 m-0"
                            aria-label="Close"></button>
                    </div>
                @endif

                <div class="input-group mb-3">
                    <div class="input-group-text bg-light">
                        <i class="bi bi-person-fill text-secondary login-input-icon-person"></i>
                    </div>
                    <input type="text" wire:model="username" class="form-control bg-transparent"
                        placeholder="Username" autocomplete="username" autofocus>
                </div>

                <div class="input-group mb-3">
                    <div class="input-group-text bg-light">
                        <i class="bi bi-key-fill text-secondary login-input-icon-key"></i>
                    </div>
                    <input type="password" wire:model="password" class="form-control bg-transparent"
                        placeholder="Password" autocomplete="current-password">
                </div>

                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                    <input class="form-check-input m-0" type="checkbox" wire:model="remember" id="rememberMe">
                    <label class="form-check-label text-secondary m-0" for="rememberMe">
                        Remember Me
                    </label>
                </div>

                <div wire:loading wire:target="login" class="w-100">
                    <div
                        class="alert alert-danger bg-danger text-center py-3 mb-3 d-flex flex-column align-items-center justify-content-center w-100">
                        <div class="spinner-border text-white mb-2" role="status"></div>
                        <div class="fw-normal text-white">Please Wait, Checking Credentials</div>
                    </div>
                </div>

                <!--begin::Row-->
                <div class="d-grid mb-3">
                    <button type="submit"
                        class="btn btn-primary py-2 d-flex align-items-center justify-content-center gap-2 fw-normal login-submit-btn"
                        wire:loading.attr="disabled">
                        <span>Sign In</span>
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
