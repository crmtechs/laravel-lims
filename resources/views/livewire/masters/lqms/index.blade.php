<div>
    <div class="app-content pt-4">
        <div class="container-fluid">
            @if (session()->has('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4 border-0 shadow-sm" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="card card-secondary card-outline">
                <div class="card-header">
                    <h3 class="card-title">LQMs Masters List</h3>
                    <div class="card-tools">
                        <div class="input-group input-group-sm" style="width: 16rem">
                            <span class="input-group-text">
                                <i class="bi bi-search" aria-hidden="true"></i>
                            </span>
                            <input wire:model.live="search" type="search" class="form-control"
                                placeholder="Filter rows&hellip;" aria-label="Filter rows" />
                        </div>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div class="d-flex gap-2 p-3 border-bottom">
                        <select wire:model.live="statusFilter" class="form-select form-select-sm" style="width: 150px;">
                            <option value="">All Statuses</option>
                            <option value="Active">Active</option>
                            <option value="Draft">Draft</option>
                            <option value="Expired">Expired</option>
                            <option value="Under Review">Under Review</option>
                        </select>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>Document Name</th>
                                    <th>Document Title</th>
                                    <th>Status</th>
                                    <th>Publish Date</th>
                                    <th>Assigned To</th>
                                    <th>Date Created</th>
                                    <th class="text-end">Edit</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lqms as $lqm)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <i class="bi bi-file-earmark-pdf text-danger fs-5"></i>
                                                <a href="{{ route('masters.lqms.show', $lqm->uuid) }}"
                                                    class="btn btn-link text-decoration-none p-0 text-start fw-semibold text-dark"
                                                    wire:navigate>
                                                    {{ $lqm->document_name }}
                                                </a>
                                            </div>
                                        </td>
                                        <td>{{ $lqm->document_title ?: '' }}</td>
                                        <td>
                                            @if ($lqm->status_id === 'Active')
                                                <span class="badge text-bg-success">{{ $lqm->status_id }}</span>
                                            @elseif($lqm->status_id === 'Draft')
                                                <span class="badge text-bg-warning">{{ $lqm->status_id }}</span>
                                            @elseif($lqm->status_id === 'Expired')
                                                <span class="badge text-bg-danger">{{ $lqm->status_id }}</span>
                                            @else
                                                <span class="badge text-bg-secondary">{{ $lqm->status_id }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $lqm->publish_date ? $lqm->publish_date->format('d/m/Y') : '' }}
                                        </td>
                                        <td>{{ $lqm->assignedUser ? $lqm->assignedUser->name : '' }}</td>
                                        <td>{{ $lqm->created_at ? $lqm->created_at->format('d/m/Y H:i') : '' }}</td>
                                        <td class="text-end">
                                            <a href="{{ route('masters.lqms.edit', $lqm->uuid) }}"
                                                class="btn btn-outline-primary btn-sm" title="Edit" wire:navigate>
                                                <i class="bi bi-pencil"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-2 text-secondary">
                                            No Records Found
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if ($lqms->hasPages())
                    <div class="card-footer clearfix">
                        <div class="float-end">
                            {{ $lqms->links() }}
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
