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
                    <a href="{{ route('masters.forms.show', $form->uuid) }}" wire:navigate class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-arrow-left"></i>
                        <span class="text-uppercase ms-2">BACK</span>
                    </a>
                </div>
            </div>

            <!-- Revision Details Panel -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title fw-semibold">{{ __('global.revision_details') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Row 1 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.document_name') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $form->document_name ?: '' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.latest_revision') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $form->activeRevision?->revision ?: '-' }}</div>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.file_name') }}</label>
                            @if (!empty($revision->file_path))
                                <div class="input-group">
                                    <div class="form-control bg-light text-dark text-truncate">{{ $revision->file_name }}</div>
                                    <a wire:click="downloadRevisionFile" class="input-group-text text-decoration-none cursor-pointer" role="button" title="Download">
                                        <i class="bi bi-download me-1"></i> {{ __('global.download') }}
                                    </a>
                                </div>
                            @else
                                <div class="form-control bg-light text-muted">{{ __('forms_master.no_file_uploaded') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.revision') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $revision->revision ?: '' }}</div>
                        </div>

                        <!-- Row 3 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.change_log') }}</label>
                            <div class="form-control bg-light h-auto min-h-5rem">
                                {{ $revision->change_log ?: '' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('global.created_at') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $revision->created_at->format(config('app.datetime_format')) }}{{ $revision->createdBy ? ' by ' . $revision->createdBy->name : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
