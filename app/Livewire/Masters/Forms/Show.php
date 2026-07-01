<?php

namespace App\Livewire\Masters\Forms;

use Livewire\Component;
use App\Models\Forms_Master;
use App\Models\Forms_Masters_Revision;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;

class Show extends Component
{
    public $form;
    public $form_revisions;

    public function mount($uuid)
    {
        $this->form = Forms_Master::findOrFail($uuid);
        $this->form_revisions = Forms_Masters_Revision::where('forms_master_uuid', $uuid)->orderByDesc('created_at')->get();
    }

    public function delete()
    {
        $this->form->delete();
        session()->flash('success', 'Form record deleted successfully.');
        return $this->redirectRoute('masters.forms', navigate: true);
    }

    public function downloadFile()
    {
        $activeRevision = $this->form->activeRevision;
        if ($activeRevision && $activeRevision->file_path && Storage::disk('public')->exists($activeRevision->file_path))
        {
            $downloadName = $activeRevision->file_name ?? $this->form->document_name;
            return Storage::disk('public')->download($activeRevision->file_path, $downloadName);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function downloadRevisionFile($revisionUuid)
    {
        $revision = Forms_Masters_Revision::findOrFail($revisionUuid);
        if ($revision->file_path && Storage::disk('public')->exists($revision->file_path))
        {
            return Storage::disk('public')->download($revision->file_path, $revision->file_name);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function render()
    {
        return view('livewire.masters.forms.show', [
            'users' => User::all(),
        ])->title('Show Form');
    }
}
