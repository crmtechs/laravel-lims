<?php

namespace App\Livewire\Masters\Lqms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LQMs_Master;
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
        $this->active_filter_document_name = session('lqms_active_filter_document_name', '');
        $this->active_filter_publish_date = session('lqms_active_filter_publish_date', '');
        $this->active_filter_status = session('lqms_active_filter_status', '');

        $this->filter_document_name = $this->active_filter_document_name;
        $this->filter_publish_date = $this->active_filter_publish_date;
        $this->filter_status = $this->active_filter_status;

        $this->showFilters = session('lqms_filters_open', false);
    }

    public function updatedShowFilters($value)
    {
        session(['lqms_filters_open' => $value]);
    }

    public function applyFilters()
    {
        $this->selected = [];
        $this->active_filter_document_name = $this->filter_document_name;
        $this->active_filter_publish_date = $this->filter_publish_date;
        $this->active_filter_status = $this->filter_status;
        
        session([
            'lqms_active_filter_document_name' => $this->active_filter_document_name,
            'lqms_active_filter_publish_date' => $this->active_filter_publish_date,
            'lqms_active_filter_status' => $this->active_filter_status,
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
            'lqms_active_filter_document_name',
            'lqms_active_filter_publish_date',
            'lqms_active_filter_status',
        ]);

        $this->showFilters = false;
        session(['lqms_filters_open' => false]);

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
        $lqm = LQMs_Master::where('uuid', $uuid)->first();
        if ($lqm) {
            $lqm->delete();
            session()->flash('success', 'LQM master deleted successfully.');
        } else {
            session()->flash('error', 'LQM master not found.');
        }
    }

    protected function getFilteredQuery()
    {
        $query = LQMs_Master::with('assignedUser')->orderBy('created_at', 'desc');

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

        $records = LQMs_Master::with('assignedUser')->whereIn('uuid', $this->selected)->get();

        return response()->streamDownload(function () use ($records) {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel rendering
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                __('lqms_master.document_name'),
                __('lqms_master.document_title'),
                __('lqms_master.status'),
                __('lqms_master.publish_date'),
                __('lqms_master.expiration_date'),
                __('lqms_master.assigned_user'),
                __('lqms_master.date_created'),
                __('lqms_master.description')
            ]);

            foreach ($records as $record) {
                fputcsv($file, [
                    $record->document_name,
                    $record->document_title,
                    $record->status,
                    $record->publish_date ? $record->publish_date->format('Y-m-d') : '',
                    $record->expiration_date ? $record->expiration_date->format('Y-m-d') : '',
                    $record->assignedUser ? $record->assignedUser->name : '',
                    $record->created_at ? $record->created_at->format('Y-m-d H:i') : '',
                    $record->description,
                ]);
            }
            fclose($file);
        }, 'lqms_export_' . date('Ymd_His') . '.csv');
    }

    public function render()
    {
        $query = $this->getFilteredQuery();

        $totalRecords = LQMs_Master::count();

        return view('livewire.masters.lqms.index', [
            'lqms' => $query->paginate(),
            'totalRecords' => $totalRecords
        ])->title('LQMs');
    }
}
