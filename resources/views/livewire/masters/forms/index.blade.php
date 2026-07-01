@push('scripts')
    <script src="{{ asset('js/masters/forms/index.js') }}?v={{ filemtime(public_path('js/masters/forms/index.js')) }}"></script>
@endpush
<div>
    <div class="app-content pt-4">
        <div class="container-fluid" x-data="formIndexFilters({{ $this->hasActiveFilters() ? 'true' : 'false' }})">
            @if (session()->has('success') || session()->has('error'))
                <div x-data="formIndexToast('{{ addslashes(session('success')) }}', '{{ addslashes(session('error')) }}')"></div>
            @endif

            <!-- Page Callout -->
            <div class="callout callout-primary bg-white rounded shadow-sm w-100 d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0 text-dark fw-light">
                        <i class="bi bi-list-ul"></i>
                    </h2>
                    <h2 class="mb-0 ms-3 text-dark fw-light">
                        {{ __('forms_master.title') }}
                    </h2>
                </div>
                <div class="btn-toolbar gap-2">
                    @if($this->hasActiveFilters())
                        <button type="button" class="btn btn-danger d-flex align-items-center" wire:click="resetFilters" title="Reset Filters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                            <span class="text-uppercase ms-2">RESET</span>
                        </button>
                    @endif
                    <button type="button" class="btn btn-primary d-flex align-items-center" @click="showFilters = !showFilters" title="Filter Records">
                        <i class="bi bi-funnel"></i>
                        <span class="text-uppercase ms-2">FILTER</span>
                    </button>
                </div>
            </div>

            <!-- Inline Filters -->
            <div x-show="showFilters" x-transition x-cloak class="card card-secondary card-outline mb-4">
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('forms_master.document_name') }}</label>
                            <input wire:model="filter_document_name" type="text" class="form-control" placeholder="Enter document name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('forms_master.publish_date') }}</label>
                            <input wire:model="filter_publish_date" type="date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('forms_master.status') }}</label>
                            <div wire:ignore x-data="choicesSelect('filter_status')">
                                <select x-ref="select" class="form-select">
                                    <option value="">{{ __('forms_master.all_statuses') }}</option>
                                    @foreach(config('dropdowns.document_status_list') as $key => $label)
                                        <option value="{{ $key }}" @if($filter_status == $key) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 d-flex justify-content-end gap-2">
                        <button type="button" class="btn btn-primary" wire:click="applyFilters">
                            <i class="bi bi-search"></i> Search
                        </button>
                        <button type="button" class="btn btn-secondary" wire:click="clearFilters">
                            <i class="bi bi-eraser"></i> Clear
                        </button>
                    </div>
                </div>
            </div>

            <div class="card card-secondary card-outline">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-header-custom">
                                <tr>
                                    <th>{{ __('forms_master.document_name') }}</th>
                                    <th>{{ __('forms_master.document_title') }}</th>
                                    <th>{{ __('forms_master.status') }}</th>
                                    <th>{{ __('forms_master.publish_date') }}</th>
                                    <th>{{ __('forms_master.assigned_to') }}</th>
                                    <th>{{ __('forms_master.date_created') }}</th>
                                    <th class="text-end">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($forms as $form)
                                    @php
                                        $statusKey = strtolower($form->status);
                                        $statusLabel = config('dropdowns.document_status_list.'.$statusKey, $form->status);
                                    @endphp
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-file-earmark-text fs-5"></i>
                                                <a href="{{ route('masters.forms.show', $form->uuid) }}"
                                                    class="btn btn-link text-decoration-none p-0 text-start fw-semibold text-dark"
                                                    wire:navigate>
                                                    {{ $form->document_name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $form->document_title ?: '' }}</td>
                                        <td>
                                            @if ($statusKey === 'active')
                                                <span class="badge text-bg-success">{{ $statusLabel }}</span>
                                            @elseif($statusKey === 'draft')
                                                <span class="badge text-bg-warning">{{ $statusLabel }}</span>
                                            @elseif($statusKey === 'expired')
                                                <span class="badge text-bg-danger">{{ $statusLabel }}</span>
                                            @elseif($statusKey === 'under review')
                                                <span class="badge text-bg-info">{{ $statusLabel }}</span>
                                            @else
                                                <span class="badge text-bg-secondary">{{ $statusLabel }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $form->publish_date ? $form->publish_date->format('d/m/Y') : '' }}
                                        </td>
                                        <td>{{ $form->assignedUser ? $form->assignedUser->name : '' }}</td>
                                        <td>{{ $form->created_at ? $form->created_at->format('d/m/Y H:i') : '' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('masters.forms.edit', $form->uuid) }}"
                                                class="btn btn-outline-primary btn-sm" title="Edit" wire:navigate>
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-2 text-secondary">
                                            {{ __('forms_master.no_records_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $forms->links(data: ['totalRecords' => $totalRecords ?? null]) }}
                </div>
            </div>
        </div>
    </div>
</div>
