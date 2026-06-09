@push('meta')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

<div>
    <!-- Welcome Info -->
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm border-0">
                <div class="card-body p-4">
                    <h4 class="text-primary fw-bold">Welcome back, {{ auth()->user()->name }}!</h4>
                    <p class="text-secondary mb-0">This is the Laboratory Information Management System (LIMS) home
                        portal. Manage your lab results, samples, and workflows securely.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row">
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm mb-3">
                <span class="info-box-icon bg-primary elevation-1"><i class="bi bi-gear-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Active Tests</span>
                    <span class="info-box-number">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm mb-3">
                <span class="info-box-icon bg-success elevation-1"><i class="bi bi-people-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Lab Technicians</span>
                    <span class="info-box-number">1</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm mb-3">
                <span class="info-box-icon bg-warning elevation-1"><i
                        class="bi bi-file-earmark-medical-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Pending Reports</span>
                    <span class="info-box-number">0</span>
                </div>
            </div>
        </div>
        <div class="col-12 col-sm-6 col-md-3">
            <div class="info-box shadow-sm mb-3">
                <span class="info-box-icon bg-danger elevation-1"><i class="bi bi-shield-lock-fill"></i></span>
                <div class="info-box-content">
                    <span class="info-box-text">Roles Configured</span>
                    <span class="info-box-number">0</span>
                </div>
            </div>
        </div>
    </div>
</div>
