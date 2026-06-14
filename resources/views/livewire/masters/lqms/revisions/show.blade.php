<div>
    <div class="app-content pt-4 pb-3">
        <div class="container-fluid">
            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <!-- Record header and action bar -->
            <div class="callout callout-primary bg-white rounded shadow-sm w-100 d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0 text-dark fw-light">
                        <i class="bi bi-file-earmark-text"></i>
                    </h2>
                    <h2 class="mb-0 ms-3 text-dark fw-light" title="Revision {{ $revision->revision }}">
                        Revision {{ $revision->revision }}
                    </h2>
                </div>
                <div class="btn-toolbar gap-2">
                    <a href="{{ route('masters.lqms.show', $lqm->uuid) }}" wire:navigate class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-arrow-left"></i>
                        <span class="text-uppercase ms-2">BACK</span>
                    </a>
                </div>
            </div>

            <!-- Revision Details Panel -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title fw-semibold">Revision Details</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Row 1 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Document Name</label>
                            <div class="form-control form-control-view bg-light">{{ $lqm->document_name ?: '' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Latest Revision</label>
                            <div class="form-control form-control-view bg-light">{{ $lqm->activeRevision?->revision ?: '-' }}</div>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">File Name</label>
                            @if (!empty($revision->file_path))
                                <div class="input-group">
                                    <div class="form-control bg-light text-dark text-truncate">{{ $revision->file_name }}</div>
                                    <a wire:click="downloadRevisionFile" class="input-group-text text-decoration-none" role="button" style="cursor: pointer;" title="Download">
                                        <i class="bi bi-download me-1"></i> Download
                                    </a>
                                </div>
                            @else
                                <div class="form-control bg-light text-muted">No file uploaded</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Revision</label>
                            <div class="form-control form-control-view bg-light">{{ $revision->revision ?: '' }}</div>
                        </div>

                        <!-- Row 3 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Change Log</label>
                            <div class="form-control bg-light h-auto min-h-5rem">
                                {{ $revision->change_log ?: '' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">Date Created</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $revision->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
