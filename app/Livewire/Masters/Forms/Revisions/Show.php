<?php

namespace App\Livewire\Masters\Forms\Revisions;

use Livewire\Component;
use App\Models\Forms_Master;
use App\Models\Forms_Masters_Revision;
use Illuminate\Support\Facades\Storage;

class Show extends Component
{
    public $form;
    public $revision;

    public function mount($uuid, $revisionUuid)
    {
        $this->form = Forms_Master::findOrFail($uuid);
        $this->revision = Forms_Masters_Revision::findOrFail($revisionUuid);

        if ($this->revision->forms_master_uuid !== $this->form->uuid)
        {
            abort(404);
        }


    }

    public function downloadRevisionFile()
    {
        if ($this->revision->file_path && Storage::disk('public')->exists($this->revision->file_path))
        {
            return Storage::disk('public')->download($this->revision->file_path, $this->revision->file_name);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function render()
    {
        return view('livewire.masters.forms.revisions.show')->title('Revision Details');
    }
}
