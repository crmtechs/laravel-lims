@push('scripts')
    <script src="{{ asset('js/masters/annexures/index.js') }}?v={{ filemtime(public_path('js/masters/annexures/index.js')) }}"></script>
@endpush
<div>
    <div class="app-content pt-4">
        <div class="container-fluid" x-data="{ showFilters: @entangle('showFilters').live }">
            @if (session()->has('success') || session()->has('error'))
                <div x-data="annexureIndexToast('{{ addslashes(session('success')) }}', '{{ addslashes(session('error')) }}')"></div>
            @endif

            <!-- Page Callout -->
            <div class="callout callout-primary bg-white rounded shadow-sm w-100 d-flex align-items-center justify-content-between mb-4">
                <div class="d-flex align-items-center">
                    <h2 class="mb-0 text-dark fw-light">
                        <i class="bi bi-list-ul"></i>
                    </h2>
                    <h2 class="mb-0 ms-3 text-dark fw-light">
                        {{ __('annexures_master.title') }}
                    </h2>
                </div>
                <div class="btn-toolbar gap-2">
                    @if(count($selected) > 0)
                        <button type="button" class="btn btn-success d-flex align-items-center" wire:click="exportSelected" title="Export Selected">
                            <i class="bi bi-file-earmark-excel"></i>
                            <span class="text-uppercase ms-2">EXPORT ({{ count($selected) }})</span>
                        </button>
                    @endif
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary d-flex align-items-center" @click="showFilters = true" title="Filter Records">
                            <i class="bi bi-funnel"></i>
                            <span class="text-uppercase ms-2">FILTER</span>
                        </button>
                        @if($this->hasActiveFilters())
                            <button type="button" class="btn btn-primary d-flex align-items-center border-start border-white border-opacity-25" wire:click="resetFilters" title="Reset Filters">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Inline Filters -->
            <div x-show="showFilters" x-transition x-cloak class="card card-secondary card-outline mb-4">
                <div class="card-header">
                    <h3 class="card-title fw-semibold mt-1"><i class="bi bi-funnel me-2"></i>Filter Records</h3>
                    <div class="card-tools">
                        <div class="btn-toolbar gap-2 me-2">
                            <button type="button" class="btn btn-sm btn-primary d-flex align-items-center" wire:click="applyFilters">
                                <i class="bi bi-search"></i>
                                <span class="text-uppercase ms-2">SEARCH</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary d-flex align-items-center" wire:click="clearFilters">
                                <i class="bi bi-eraser"></i>
                                <span class="text-uppercase ms-2">CLEAR</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-danger" @click="showFilters = false" title="Close">
                                <i class="bi bi-x-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('annexures_master.document_name') }}</label>
                            <input wire:model="filter_document_name" type="text" class="form-control" placeholder="Enter document name">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('annexures_master.publish_date') }}</label>
                            <input wire:model="filter_publish_date" type="date" class="form-control">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold">{{ __('annexures_master.status') }}</label>
                            <div wire:ignore x-data="choicesSelect('filter_status')">
                                <select x-ref="select" class="form-select">
                                    <option value="">{{ __('annexures_master.all_statuses') }}</option>
                                    @foreach(config('dropdowns.document_status_list') as $key => $label)
                                        <option value="{{ $key }}" @if($filter_status == $key) selected @endif>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card card-primary card-outline">
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-header-custom">
                                <tr>
                                    <th>
                                        <div class="dropdown d-inline-block">
                                            <button class="btn btn-sm btn-outline-secondary dropdown-toggle p-0 border-0 shadow-none text-dark fw-bold bg-transparent" type="button" data-bs-toggle="dropdown" data-bs-popper-config='{"strategy":"fixed"}' aria-expanded="false">
                                                <i class="bi bi-check2-square fs-5"></i>
                                            </button>
                                            <ul class="dropdown-menu shadow text-sm">
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="selectPage">Select This Page ({{ $annexures->count() }})</a></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="selectAll">Select All ({{ $annexures->total() }})</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li><a class="dropdown-item" href="#" wire:click.prevent="deselectAll">Deselect All</a></li>
                                            </ul>
                                        </div>
                                    </th>
                                    <th class="w-20">{{ __('annexures_master.document_name') }}</th>
                                    <th class="w-20">{{ __('annexures_master.document_title') }}</th>
                                    <th class="w-15">{{ __('annexures_master.status') }}</th>
                                    <th class="w-15">{{ __('annexures_master.publish_date') }}</th>
                                    <th class="w-15">{{ __('annexures_master.assigned_to') }}</th>
                                    <th class="w-15">{{ __('annexures_master.created_at') }}</th>
                                    <th class="text-end">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($annexures as $annexure)
                                    @php
                                        $statusKey = strtolower($annexure->status);
                                        $statusLabel = config('dropdowns.document_status_list.'.$statusKey, $annexure->status);
                                    @endphp
                                    <tr wire:key="annexure-{{ $annexure->uuid }}">
                                        <td>
                                            <input type="checkbox" wire:key="checkbox-{{ $annexure->uuid }}" wire:model.live="selected" value="{{ $annexure->uuid }}" class="form-check-input cursor-pointer">
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-file-earmark-text fs-5"></i>
                                                <a href="{{ route('masters.annexures.show', $annexure->uuid) }}"
                                                    class="btn btn-link text-decoration-none p-0 text-start fw-semibold text-dark"
                                                    wire:navigate>
                                                    {{ $annexure->document_name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $annexure->document_title ?: '' }}</td>
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
                                        <td>{{ $annexure->publish_date ? $annexure->publish_date->format(config('app.date_format')) : '' }}
                                        </td>
                                        <td>{{ $annexure->assignedTo ? $annexure->assignedTo->name : '' }}</td>
                                        <td>{{ $annexure->created_at ? $annexure->created_at->format(config('app.datetime_format')) : '' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('masters.annexures.edit', $annexure->uuid) }}"
                                                class="btn btn-outline-primary btn-sm" title="Edit" wire:navigate>
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center py-2 text-secondary">
                                            {{ __('annexures_master.no_records_found') }}
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    {{ $annexures->links(data: ['totalRecords' => $totalRecords ?? null]) }}
                </div>
            </div>
        </div>
    </div>
</div>
