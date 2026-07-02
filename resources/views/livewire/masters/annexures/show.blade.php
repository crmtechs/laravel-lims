@push('scripts')
    <script src="{{ asset('js/masters/annexures/show.js') }}?v={{ filemtime(public_path('js/masters/annexures/show.js')) }}"></script>
@endpush
<div>
    <div class="app-content pt-4 pb-3">
        <div class="container-fluid">
            @if (session()->has('success') || session()->has('error'))
                <div x-data="annexureShowToast('{{ addslashes(session('success')) }}', '{{ addslashes(session('error')) }}')"></div>
            @endif

            <!-- Record header and action bar -->
            <div class="callout callout-primary bg-white rounded shadow-sm w-100 d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0 text-dark fw-light">
                        <i class="bi bi-file-earmark-text"></i>
                    </h2>
                    <h2 class="mb-0 ms-3 text-dark fw-light" title="{{ $annexure->document_name }}">
                        {{ $annexure->document_name }}
                    </h2>
                </div>
                <div class="btn-toolbar gap-2">
                    <a href="{{ route('masters.annexures.edit', $annexure->uuid) }}" wire:navigate class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-pencil"></i>
                        <span class="text-uppercase ms-2">EDIT</span>
                    </a>
                    <button type="button" @click="confirmAnnexureDelete($wire)" class="btn btn-danger d-flex align-items-center">
                        <i class="bi bi-trash"></i>
                        <span class="text-uppercase ms-2">DELETE</span>
                    </button>
                    <a href="{{ route('masters.annexures') }}" wire:navigate class="btn btn-outline-secondary d-flex align-items-center">
                        <i class="bi bi-arrow-left"></i>
                        <span class="text-uppercase ms-2">BACK</span>
                    </a>
                </div>
            </div>

            <!-- Annexure Details Panel -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title fw-semibold">{{ __('annexures_master.details') }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Row 1 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.file_name') }}</label>
                            @if (!empty($annexure->activeRevision?->file_path))
                                <div class="input-group">
                                    <div class="form-control bg-light text-dark text-truncate">{{ $annexure->activeRevision?->file_name ?? $annexure->document_name }}</div>
                                    <a wire:click="downloadFile" class="input-group-text text-decoration-none" role="button" style="cursor: pointer;" title="Download">
                                        <i class="bi bi-download me-1"></i> {{ __('annexures_master.download') }}
                                    </a>
                                </div>
                            @else
                                <div class="form-control bg-light text-muted">{{ __('annexures_master.no_file_uploaded') }}</div>
                            @endif
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.revision') }}</label>
                            <div class="form-control bg-light">{{ $annexure->activeRevision ? $annexure->activeRevision->revision : '-' }}</div>
                        </div>

                        <!-- Row 2 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.document_name') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $annexure->document_name ?: '' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.document_title') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $annexure->document_title ?: '' }}</div>
                        </div>

                        <!-- Row 3 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.publish_date') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $annexure->publish_date?->format(config('app.date_format')) }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.expiration_date') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $annexure->expiration_date?->format(config('app.date_format')) }}
                            </div>
                        </div>

                        <!-- Row 4 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.status') }}</label>
                            <div class="form-control form-control-view bg-light">{{ $annexure->status ? config('dropdowns.document_status_list.' . strtolower($annexure->status), $annexure->status) : '' }}</div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.assigned_to') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $annexure->assignedTo ? $annexure->assignedTo->name : '' }}
                            </div>
                        </div>

                        <!-- Row 5 -->
                        <div class="col-12 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.description') }}</label>
                            <div class="form-control bg-light h-auto min-h-5rem">
                                {{ $annexure->description ?: '' }}
                            </div>
                        </div>

                        <!-- Row 6 -->
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.created_at') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $annexure->created_at ? $annexure->created_at->format(config('app.datetime_format')) : '' }}{{ $annexure->createdBy ? ' by ' . $annexure->createdBy->name : '' }}
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-bold">{{ __('annexures_master.updated_at') }}</label>
                            <div class="form-control form-control-view bg-light">
                                {{ $annexure->updated_at ? $annexure->updated_at->format(config('app.datetime_format')) : '' }}{{ $annexure->updatedBy ? ' by ' . $annexure->updatedBy->name : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Revision History Subpanel -->
            <div class="card card-secondary card-outline mt-4">
                <div class="card-header">
                    <h3 class="card-title fw-semibold mb-0 mt-1"><i class="bi bi-clock-history me-2"></i>{{ __('annexures_master.revision_history') }}</h3>
                    <div class="btn-toolbar gap-2 float-end">
                        <a href="{{ route('masters.annexures.revision.create', $annexure->uuid) }}" wire:navigate class="btn btn-sm btn-primary">
                            <i class="bi bi-plus-lg me-1"></i> Create
                        </a>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>{{ __('annexures_master.file_name') }}</th>
                                    <th>{{ __('annexures_master.change_log') }}</th>
                                    <th>{{ __('annexures_master.revision') }}</th>
                                    <th>{{ __('annexures_master.created_at') }}</th>
                                    <th>{{ __('annexures_master.created_by') }}</th>
                                    <th class="text-center" style="width: 100px;">{{ __('annexures_master.download') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($annexure_revisions as $revision)
                                    <tr>
                                        <td class="align-middle">
                                            @if($revision->file_name)
                                                <a href="{{ route('masters.annexures.revision.show', ['uuid' => $annexure->uuid, 'revisionUuid' => $revision->uuid]) }}" wire:navigate class="text-decoration-none">
                                                    {{ $revision->file_name }}
                                                </a>
                                            @endif
                                        </td>
                                        <td class="align-middle text-break">{{ $revision->change_log }}</td>
                                        <td class="align-middle">
                                            {{ $revision->revision }}
                                            @if($annexure->annexures_masters_revision_uuid === $revision->uuid)
                                                <span class="badge bg-success ms-2">Latest</span>
                                            @endif
                                        </td>
                                        <td class="align-middle">{{ $revision->created_at->format(config('app.datetime_format')) }}</td>
                                        <td class="align-middle">{{ $revision->createdBy?->name }}</td>
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
                                        <td colspan="6" class="text-center text-muted py-3">{{ __('annexures_master.no_revisions_found') }}</td>
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
