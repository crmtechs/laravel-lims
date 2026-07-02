<?php

namespace App\Livewire\Masters\Annexures;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Annexures_Master;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\On;

class Index extends Component
{
    use WithPagination;

    public $filter_document_name = '';
    public $filter_publish_date = '';
    public $filter_status = '';

    public $selected = [];

    public $active_filter_document_name = '';
    public $active_filter_publish_date = '';
    public $active_filter_status = '';

    public $showFilters = false;

    public function mount()
    {
        $this->active_filter_document_name = session('annexures_active_filter_document_name', '');
        $this->active_filter_publish_date = session('annexures_active_filter_publish_date', '');
        $this->active_filter_status = session('annexures_active_filter_status', '');

        $this->filter_document_name = $this->active_filter_document_name;
        $this->filter_publish_date = $this->active_filter_publish_date;
        $this->filter_status = $this->active_filter_status;

        $this->showFilters = session('annexures_filters_open', false);
    }

    public function updatedShowFilters($value)
    {
        session(['annexures_filters_open' => $value]);
    }

    public function applyFilters()
    {
        $this->selected = [];
        $this->active_filter_document_name = $this->filter_document_name;
        $this->active_filter_publish_date = $this->filter_publish_date;
        $this->active_filter_status = $this->filter_status;
        
        session([
            'annexures_active_filter_document_name' => $this->active_filter_document_name,
            'annexures_active_filter_publish_date' => $this->active_filter_publish_date,
            'annexures_active_filter_status' => $this->active_filter_status,
        ]);

        $this->resetPage();
    }

    public function clearFilters()
    {
        $this->filter_document_name = '';
        $this->filter_publish_date = '';
        $this->filter_status = '';
    }

    public function resetFilters()
    {
        $this->selected = [];
        $this->clearFilters();
        $this->active_filter_document_name = '';
        $this->active_filter_publish_date = '';
        $this->active_filter_status = '';
        
        session()->forget([
            'annexures_active_filter_document_name',
            'annexures_active_filter_publish_date',
            'annexures_active_filter_status',
        ]);

        $this->showFilters = false;
        session(['annexures_filters_open' => false]);

        $this->resetPage();
    }

    public function hasActiveFilters(): bool
    {
        return !empty($this->active_filter_document_name) || 
               !empty($this->active_filter_publish_date) || 
               !empty($this->active_filter_status);
    }

    public function delete($uuid)
    {
        $annexure = Annexures_Master::where('uuid', $uuid)->first();
        if ($annexure) {
            $annexure->delete();
            session()->flash('success', 'Annexure master deleted successfully.');
        } else {
            session()->flash('error', 'Annexure master not found.');
        }
    }

    protected function getFilteredQuery()
    {
        $query = Annexures_Master::with('assignedTo')->orderBy('created_at', 'desc');

        if (!empty($this->active_filter_document_name)) {
            $query->where('document_name', 'like', '%' . $this->active_filter_document_name . '%');
        }

        if (!empty($this->active_filter_publish_date)) {
            $query->whereDate('publish_date', $this->active_filter_publish_date);
        }

        if (!empty($this->active_filter_status)) {
            $query->where('status', $this->active_filter_status);
        }

        return $query;
    }

    public function selectAll()
    {
        $this->selected = $this->getFilteredQuery()->pluck('uuid')->map(fn($uuid) => (string) $uuid)->toArray();
    }

    public function selectPage()
    {
        $pageIds = $this->getFilteredQuery()->paginate()->pluck('uuid')->map(fn($uuid) => (string) $uuid)->toArray();
        $this->selected = array_values(array_unique(array_merge($this->selected, $pageIds)));
    }

    public function deselectAll()
    {
        $this->selected = [];
    }

    public function exportSelected()
    {
        if (empty($this->selected)) {
            return;
        }

        $records = Annexures_Master::with(['assignedTo', 'createdBy', 'updatedBy', 'activeRevision.createdBy'])->whereIn('uuid', $this->selected)->get();

        return response()->streamDownload(function () use ($records) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel rendering
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                __('annexures_master.document_name'),
                __('annexures_master.document_title'),
                __('annexures_master.description'),
                __('annexures_master.status'),
                __('annexures_master.publish_date'),
                __('annexures_master.expiration_date'),
                __('annexures_master.assigned_to'),
                __('annexures_master.created_at'),
                __('annexures_master.created_by'),
                __('annexures_master.updated_at'),
                __('annexures_master.updated_by'),
                __('annexures_master.file_name'),
                __('annexures_master.latest_revision'),
                __('annexures_master.change_log'),
                __('annexures_master.revision_created_at'),
                __('annexures_master.file_uploaded_by')
            ]);

            foreach ($records as $record)
            {
                fputcsv($file, [
                    $record->document_name,
                    $record->document_title,
                    $record->description,
                    $record->status,
                    $record->publish_date ? $record->publish_date->format(config('app.date_format')) : '',
                    $record->expiration_date ? $record->expiration_date->format(config('app.date_format')) : '',
                    $record->assignedTo ? $record->assignedTo->name : '',
                    $record->created_at ? $record->created_at->format(config('app.datetime_format')) : '',
                    $record->createdBy ? $record->createdBy->name : '',
                    $record->updated_at ? $record->updated_at->format(config('app.datetime_format')) : '',
                    $record->updatedBy ? $record->updatedBy->name : '',
                    $record->activeRevision ? $record->activeRevision->file_name : '',
                    $record->activeRevision ? $record->activeRevision->revision : '',
                    $record->activeRevision ? $record->activeRevision->change_log : '',
                    $record->activeRevision && $record->activeRevision->created_at ? $record->activeRevision->created_at->format(config('app.datetime_format')) : '',
                    $record->activeRevision && $record->activeRevision->createdBy ? $record->activeRevision->createdBy->name : '',
                ]);
            }
            fclose($file);
        }, 'annexures_export_' . date('Ymd_His') . '.csv');
    }
    public function render()
    {
        return view('livewire.masters.annexures.index', [
            'annexures' => $this->getFilteredQuery()->paginate()
        ]);
    }
}
