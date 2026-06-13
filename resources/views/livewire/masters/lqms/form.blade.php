<form wire:submit.prevent="save">
    <!-- Top Header block using AdminLTE 4 callout -->
    <div class="callout callout-primary bg-white rounded shadow-sm w-100 d-flex align-items-center justify-content-between mb-4">
        <div class="d-flex align-items-center">
            <h2 class="mb-0 text-dark fw-light">
                <i class="bi bi-person"></i>
            </h2>
            <h2 class="mb-0 ms-3 text-dark fw-light">
                {{ $formTitle ?? 'Create' }} LQM
            </h2>
        </div>
        <div class="btn-toolbar gap-2">
            <button type="button" wire:click="save" class="btn btn-primary d-flex align-items-center">
                <i class="bi bi-save"></i>
                <span class="text-uppercase ms-2">SAVE</span>
            </button>
            <a href="{{ route('masters.lqms') }}" wire:navigate class="btn btn-danger d-flex align-items-center">
                <i class="bi bi-caret-left-square"></i>
                <span class="text-uppercase ms-2">CANCEL</span>
            </a>
        </div>
    </div>

    <!-- Details Card with primary header -->
    <div class="card card-secondary card-outline">
        <div class="card-header">
            <h3 class="card-title fw-semibold">LQM Details</h3>
        </div>
        <div class="card-body">
            <div class="row">
                <!-- Row 1 -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">File Name <span class="text-danger">*</span></label>
                    <input wire:model="file_name" type="file" class="form-control @error('file_name') is-invalid @enderror">
                    <div wire:loading wire:target="file_name" class="text-primary small mt-1">
                        <i class="spinner-border spinner-border-sm"></i> Staging file...
                    </div>
                    @error('file_name')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                    @enderror
                    @if (!empty($file_path) && !$file_name)
                        <div class="small text-secondary mt-1">Current file: {{ $existing_file_name ?? $document_name }}</div>
                    @endif
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Revision <span class="text-danger">*</span></label>
                    <input wire:model="revision" type="text"
                        class="form-control @error('revision') is-invalid @enderror"
                        placeholder="">
                    @error('revision')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 2 -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Document Name <span class="text-danger">*</span></label>
                    <input wire:model="document_name" type="text"
                        class="form-control @error('document_name') is-invalid @enderror"
                        placeholder="Document Name">
                    @error('document_name')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Document Title</label>
                    <input wire:model="document_title" type="text"
                        class="form-control @error('document_title') is-invalid @enderror"
                        placeholder="Document Title">
                    @error('document_title')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 3 -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Publish Date <span class="text-danger">*</span></label>
                    <input wire:model="publish_date" type="date"
                        class="form-control @error('publish_date') is-invalid @enderror">
                    @error('publish_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Expiration Date</label>
                    <input wire:model="expiration_date" type="date"
                        class="form-control @error('expiration_date') is-invalid @enderror">
                    @error('expiration_date')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 4 -->
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Status <span class="text-danger">*</span></label>
                    <select wire:model="status_id" class="form-select @error('status_id') is-invalid @enderror">
                        <option value="">Select Status</option>
                        <option value="Active">Active</option>
                        <option value="Draft">Draft</option>
                        <option value="Expired">Expired</option>
                        <option value="Under Review">Under Review</option>
                    </select>
                    @error('status_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label fw-bold">Assigned User <span class="text-danger">*</span></label>
                    <select wire:model="assigned_user_id"
                        class="form-select @error('assigned_user_id') is-invalid @enderror">
                        <option value="">Select User</option>
                        @foreach ($users as $user)
                            <option value="{{ $user->uuid }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('assigned_user_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Row 5 -->
                <div class="col-12 mb-3">
                    <label class="form-label fw-bold">Description</label>
                    <textarea wire:model="description" class="form-control @error('description') is-invalid @enderror"
                        rows="3" placeholder="Description"></textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    </div>
</form>
