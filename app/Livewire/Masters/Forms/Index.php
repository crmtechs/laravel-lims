<?php

namespace App\Livewire\Masters\Forms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Forms_Master;
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
        $this->active_filter_document_name = session('forms_active_filter_document_name', '');
        $this->active_filter_publish_date = session('forms_active_filter_publish_date', '');
        $this->active_filter_status = session('forms_active_filter_status', '');

        $this->filter_document_name = $this->active_filter_document_name;
        $this->filter_publish_date = $this->active_filter_publish_date;
        $this->filter_status = $this->active_filter_status;

        $this->showFilters = session('forms_filters_open', false);
    }

    public function updatedShowFilters($value)
    {
        session(['forms_filters_open' => $value]);
    }

    public function applyFilters()
    {
        $this->selected = [];
        $this->active_filter_document_name = $this->filter_document_name;
        $this->active_filter_publish_date = $this->filter_publish_date;
        $this->active_filter_status = $this->filter_status;
        
        session([
            'forms_active_filter_document_name' => $this->active_filter_document_name,
            'forms_active_filter_publish_date' => $this->active_filter_publish_date,
            'forms_active_filter_status' => $this->active_filter_status,
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
            'forms_active_filter_document_name',
            'forms_active_filter_publish_date',
            'forms_active_filter_status',
        ]);

        $this->showFilters = false;
        session(['forms_filters_open' => false]);

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
        $form = Forms_Master::where('uuid', $uuid)->first();
        if ($form)
        {
            $form->delete();
            session()->flash('success', 'Form master deleted successfully.');
        }
        else
        {
            session()->flash('error', 'Form master not found.');
        }
    }

    protected function getFilteredQuery()
    {
        $query = Forms_Master::with('assignedTo')->orderBy('created_at', 'desc');

        if (!empty($this->active_filter_document_name))
        {
            $query->where('document_name', 'like', '%' . $this->active_filter_document_name . '%');
        }

        if (!empty($this->active_filter_publish_date))
        {
            $query->whereDate('publish_date', $this->active_filter_publish_date);
        }

        if (!empty($this->active_filter_status))
        {
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
        if (empty($this->selected))
        {
            return;
        }

        $records = Forms_Master::with('assignedTo')->whereIn('uuid', $this->selected)->get();

        return response()->streamDownload(function () use ($records)
        {
            $file = fopen('php://output', 'w');
            
            // Add UTF-8 BOM for proper Excel rendering
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            fputcsv($file, [
                __('forms_master.document_name'),
                __('forms_master.document_title'),
                __('forms_master.status'),
                __('forms_master.publish_date'),
                __('forms_master.expiration_date'),
                __('forms_master.assigned_user'),
                __('forms_master.date_created'),
                __('forms_master.description')
            ]);

            foreach ($records as $record)
            {
                fputcsv($file, [
                    $record->document_name,
                    $record->document_title,
                    $record->status,
                    $record->publish_date ? $record->publish_date->format(config('app.date_format')) : '',
                    $record->expiration_date ? $record->expiration_date->format(config('app.date_format')) : '',
                    $record->assignedTo ? $record->assignedTo->name : '',
                    $record->created_at ? $record->created_at->format(config('app.datetime_format')) : '',
                    $record->description,
                ]);
            }
            fclose($file);
        }, 'forms_export_' . date('Ymd_His') . '.csv');
    }

    public function render()
    {
        $query = $this->getFilteredQuery();

        $totalRecords = Forms_Master::count();

        return view('livewire.masters.forms.index', [
            'forms' => $query->paginate(),
            'totalRecords' => $totalRecords
        ])->title('Forms');
    }
}
