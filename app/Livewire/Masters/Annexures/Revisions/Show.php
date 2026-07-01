<?php

namespace App\Livewire\Masters\Annexures\Revisions;

use Livewire\Component;
use App\Models\Annexures_Master;
use App\Models\Annexures_Masters_Revision;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    public $annexure;
    public $revision;

    public function mount($uuid, $revisionUuid)
    {
        $this->annexure = Annexures_Master::findOrFail($uuid);
        $this->revision = Annexures_Masters_Revision::findOrFail($revisionUuid);

        if ($this->revision->annexures_master_uuid !== $this->annexure->uuid) {
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
        return view('livewire.masters.annexures.revisions.show')->title('Revision Details');
    }
}
