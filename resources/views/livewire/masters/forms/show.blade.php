@push('scripts')
    <script src="{{ asset('js/masters/forms/show.js') }}?v={{ filemtime(public_path('js/masters/forms/show.js')) }}"></script>
@endpush
<div>
    <div class="app-content pt-4 pb-3">
        <div class="container-fluid">
            @if (session()->has('success') || session()->has('error'))
                <div x-data="formShowToast('{{ addslashes(session('success')) }}', '{{ addslashes(session('error')) }}')"></div>
            @endif

            <!-- Record header and action bar -->
            <div class="callout callout-primary bg-white rounded shadow-sm w-100 d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0 text-dark fw-light">
                        <i class="bi bi-file-earmark-text"></i>
                    </h2>
                    <h2 class="mb-0 ms-3 text-dark fw-light" title="{{ $form->document_name }}">
                        {{ $form->document_name }}
                    </h2>
                </div>
                <div class="btn-toolbar gap-2">
                    <a href="{{ route('masters.forms.edit', $form->uuid) }}" wire:navigate class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-pencil"></i>
                        <span class="text-uppercase ms-2">EDIT</span>
                    </a>
                    <button type="button" @click="confirmFormDelete($wire)" class="btn btn-danger d-flex align-items-center">
                        <i class="bi bi-trash"></i>
                        <span class="text-uppercase ms-2">DELETE</span>
                    </button>
                    <a href="{{ route('masters.forms') }}" wire:navigate class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-arrow-left"></i>
                        <span class="text-uppercase ms-2">BACK</span>
                    </a>
                </div>
            </div>

            <!-- Form Details Panel -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title fw-semibold">{{ __('forms_master.details') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Row 1 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.file_name') }}</label>
                            @if (!empty($form->activeRevision?->file_path))
                                <div class="input-group">
                                    <div class="form-control bg-light text-dark text-truncate">{{ $form->activeRevision?->file_name ?? $form->document_name }}</div>
                                    <a wire:click="downloadFile" class="input-group-text text-decoration-none cursor-pointer" role="button" title="Download">
                                        <i class="bi bi-download me-1"></i> {{ __('forms_master.download') }}
                                    </a>
                                </div>
                            @else
                                <div class="form-control bg-light text-muted">{{ __('forms_master.no_file_uploaded') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.revision') }}</label>
                            <div class="form-control bg-light">{{ $form->activeRevision ? $form->activeRevision->revision : '-' }}</div>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.document_name') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $form->document_name ?: '' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.document_title') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $form->document_title ?: '' }}</div>
                        </div>

                        <!-- Row 3 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.publish_date') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $form->publish_date?->format('d/m/Y') }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.expiration_date') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $form->expiration_date?->format('d/m/Y') }}
                            </div>
                        </div>

                        <!-- Row 4 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.status') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $form->status ?: '' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.assigned_to') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $form->assignedUser ? $form->assignedUser->name : '' }}
                            </div>
                        </div>

                        <!-- Row 5 -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">{{ __('forms_master.description') }}</label>
                            <div class="form-control bg-light h-auto min-h-5rem">
                                {{ $form->description ?: '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revision History Subpanel -->
            <div class="card card-secondary card-outline mt-4">
                <div class="card-header">
                    <h3 class="card-title fw-semibold mb-0 mt-1"><i class="bi bi-clock-history me-2"></i>{{ __('forms_master.revision_history') }}</h3>
                    <div class="btn-toolbar gap-2 float-end">
                        <a href="{{ route('masters.forms.revision.create', $form->uuid) }}" wire:navigate class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Create
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('forms_master.file_name') }}</th>
                                    <th>{{ __('forms_master.change_log') }}</th>
                                    <th>{{ __('forms_master.revision') }}</th>
                                    <th>{{ __('forms_master.date_created') }}</th>
                                    <th>{{ __('forms_master.created_by') }}</th>
                                    <th class="text-center w-100px">{{ __('forms_master.download') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($form_revisions as $revision)
                                    <tr>
                                        <td class="align-middle">
                                            @if($revision->file_name)
                                                <a href="{{ route('masters.forms.revision.show', ['uuid' => $form->uuid, 'revisionUuid' => $revision->uuid]) }}" wire:navigate class="text-decoration-none">
                                                    {{ $revision->file_name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td class="align-middle text-break">{{ $revision->change_log }}</td>
                                        <td class="align-middle">
                                            {{ $revision->revision }}
                                            @if($form->forms_masters_revision_uuid === $revision->uuid)
                                                <span class="badge bg-success ms-2">Latest</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $revision->created_at->format('d/m/Y H:i') }}</td>
                                        <td class="align-middle">{{ $revision->createdUser?->name }}</td>
                                        <td class="text-center align-middle">
                                            <button type="button" wire:click="downloadRevisionFile('{{ $revision->uuid }}')"
                                                class="btn btn-sm btn-outline-primary"
                                                title="Download this revision"
                                                @if(!$revision->file_path) disabled @endif>
                                                <i class="bi bi-download"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-3">{{ __('forms_master.no_revisions_found') }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
