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

    public $active_filter_document_name = '';
    public $active_filter_publish_date = '';
    public $active_filter_status = '';

    public function mount()
    {
        $this->active_filter_document_name = session('annexures_active_filter_document_name', '');
        $this->active_filter_publish_date = session('annexures_active_filter_publish_date', '');
        $this->active_filter_status = session('annexures_active_filter_status', '');

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
        $this->clearFilters();
        $this->active_filter_document_name = '';
        $this->active_filter_publish_date = '';
        $this->active_filter_status = '';
        
        session()->forget([
            'annexures_active_filter_document_name',
            'annexures_active_filter_publish_date',
            'annexures_active_filter_status',
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
        $annexure = Annexures_Master::where('uuid', $uuid)->first();
        if ($annexure) {
            $annexure->delete();
            session()->flash('success', 'Annexure master deleted successfully.');
        } else {
            session()->flash('error', 'Annexure master not found.');
        }
    }

    public function render()
    {
        $query = Annexures_Master::with('assignedUser')->orderBy('created_at', 'desc');

        if (!empty($this->active_filter_document_name)) {
            $query->where('document_name', 'like', '%' . $this->active_filter_document_name . '%');
        }

        if (!empty($this->active_filter_publish_date)) {
            $query->whereDate('publish_date', $this->active_filter_publish_date);
        }

        if (!empty($this->active_filter_status)) {
            $query->where('status', $this->active_filter_status);
        }

        $totalRecords = Annexures_Master::count();

        return view('livewire.masters.annexures.index', [
            'annexures' => $query->paginate(),
            'totalRecords' => $totalRecords
        ])->title('Annexures');
    }
}
