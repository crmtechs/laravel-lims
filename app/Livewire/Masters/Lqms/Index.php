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

    public $active_filter_document_name = '';
    public $active_filter_publish_date = '';
    public $active_filter_status = '';

    public function applyFilters()
    {
        $this->active_filter_document_name = $this->filter_document_name;
        $this->active_filter_publish_date = $this->filter_publish_date;
        $this->active_filter_status = $this->filter_status;
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

    public function render()
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

        return view('livewire.masters.lqms.index', [
            'lqms' => $query->paginate(10)
        ])->title('LQMs');
    }
}
