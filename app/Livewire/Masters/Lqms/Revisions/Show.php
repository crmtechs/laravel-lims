<?php

namespace App\Livewire\Masters\Lqms\Revisions;

use Livewire\Component;
use App\Models\LQMs_Master;
use App\Models\LQMs_Masters_Revision;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    public $lqm;
    public $revision;

    public function mount($uuid, $revisionUuid)
    {
        $this->lqm = LQMs_Master::findOrFail($uuid);
        $this->revision = LQMs_Masters_Revision::findOrFail($revisionUuid);

        if ($this->revision->lqms_master_uuid !== $this->lqm->uuid) {
            abort(404);
        }
    }

    public function downloadRevisionFile()
    {
        if ($this->revision->file_path && Storage::disk('public')->exists($this->revision->file_path)) {
            return Storage::disk('public')->download($this->revision->file_path, $this->revision->file_name);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function render()
    {
        return view('livewire.masters.lqms.revisions.show')->title('Revision Details');
    }
}
