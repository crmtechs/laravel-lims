<?php

namespace App\Livewire\Masters\Lqms;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\LQMs_Master;

class Index extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = '';

    public function updatedSearch()
    {
        $this->resetPage();
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

        if (!empty($this->search)) {
            $query->where(function($q) {
                $q->where('document_name', 'like', '%' . $this->search . '%')
                  ->orWhere('document_title', 'like', '%' . $this->search . '%')
                  ->orWhere('description', 'like', '%' . $this->search . '%');
            });
        }

        if (!empty($this->statusFilter)) {
            $query->where('status_id', $this->statusFilter);
        }

        return view('livewire.masters.lqms.index', [
            'lqms' => $query->paginate(10)
        ])->title('LQMs');
    }
}
