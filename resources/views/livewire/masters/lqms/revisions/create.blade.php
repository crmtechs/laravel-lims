<div>
    <div class="app-content pt-4 pb-3">
        <div class="container-fluid">
        <form wire:submit="save">
            <!-- Record header and action bar -->
            <div class="callout callout-primary bg-white rounded shadow-sm w-100 d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0 text-dark fw-light">
                        <i class="bi bi-file-earmark-plus"></i>
                    </h2>
                    <h2 class="mb-0 ms-3 text-dark fw-light">
                        Create New Revision
                    </h2>
                </div>
                <div class="btn-toolbar gap-2">
                    <button type="submit" class="btn btn-primary d-flex align-items-center">
                        <div wire:loading wire:target="save" class="spinner-border spinner-border-sm me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <i wire:loading.remove wire:target="save" class="bi bi-save me-2"></i>
                        <span class="text-uppercase">SAVE</span>
                    </button>
                    <a href="{{ route('masters.lqms.show', $lqm->uuid) }}" wire:navigate class="btn btn-danger d-flex align-items-center">
                        <i class="bi bi-caret-left-square"></i>
                        <span class="text-uppercase ms-2">CANCEL</span>
                    </a>
                </div>
            </div>
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title fw-semibold">Revision Details</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Row 1: Read-only -->
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Document Name</label>
                                <div class="form-control bg-light">{{ $lqm->document_name }}</div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Latest Revision</label>
                                <div class="form-control bg-light">{{ $lqm->activeRevision?->revision ?: '-' }}</div>
                            </div>

                            <!-- Row 2: File Browse and Revision -->
                            <div class="col-md-6 mb-3">
                                <label for="file_name" class="form-label fw-bold">File Browse <span class="text-danger">*</span></label>
                                <input type="file" class="form-control @error('file_name') is-invalid @enderror" id="file_name" wire:model="file_name">
                                @error('file_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="revision" class="form-label fw-bold">Revision <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('revision') is-invalid @enderror" id="revision" wire:model="revision" placeholder="Revision">
                                @error('revision')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Row 3: Change Log -->
                            <div class="col-md-12 mb-3">
                                <label for="change_log" class="form-label fw-bold">Change Log <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('change_log') is-invalid @enderror" id="change_log" wire:model="change_log" rows="3" placeholder="Change Log"></textarea>
                                @error('change_log')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
