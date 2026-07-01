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

    public $active_filter_document_name = '';
    public $active_filter_publish_date = '';
    public $active_filter_status = '';

    public function mount()
    {
        $this->active_filter_document_name = session('forms_active_filter_document_name', '');
        $this->active_filter_publish_date = session('forms_active_filter_publish_date', '');
        $this->active_filter_status = session('forms_active_filter_status', '');

        $this->filter_document_name = $this->active_filter_document_name;
        $this->filter_publish_date = $this->active_filter_publish_date;
        $this->filter_status = $this->active_filter_status;
    }

    public function applyFilters()
    {
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
        $this->clearFilters();
        $this->active_filter_document_name = '';
        $this->active_filter_publish_date = '';
        $this->active_filter_status = '';
        
        session()->forget([
            'forms_active_filter_document_name',
            'forms_active_filter_publish_date',
            'forms_active_filter_status',
        ]);

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

    public function render()
    {
        $query = Forms_Master::with('assignedUser')->orderBy('created_at', 'desc');

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

        $totalRecords = Forms_Master::count();

        return view('livewire.masters.forms.index', [
            'forms' => $query->paginate(),
            'totalRecords' => $totalRecords
        ])->title('Forms');
    }
}
