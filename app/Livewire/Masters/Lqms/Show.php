<?php

namespace App\Livewire\Masters\Lqms;

use Livewire\Component;
use App\Models\LQMs_Master;
use App\Models\LQMs_Masters_Revision;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;

class Show extends Component
{
    public $lqm;
    public $lqm_revisions;

    public function mount($uuid)
    {
        $this->lqm = LQMs_Master::findOrFail($uuid);
        $this->lqm_revisions = LQMs_Masters_Revision::where('lqms_master_uuid', $uuid)->orderByDesc('created_at')->get();
    }

    public function delete()
    {
        $this->lqm->delete();
        session()->flash('success', 'LQM record deleted successfully.');
        return $this->redirectRoute('masters.lqms', navigate: true);
    }

    public function downloadFile()
    {
        $activeRevision = $this->lqm->activeRevision;
        if ($activeRevision && $activeRevision->file_path && Storage::disk('public')->exists($activeRevision->file_path))
        {
            $downloadName = $activeRevision->file_name ?? $this->lqm->document_name;
            return Storage::disk('public')->download($activeRevision->file_path, $downloadName);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function downloadRevisionFile($revisionUuid)
    {
        $revision = LQMs_Masters_Revision::findOrFail($revisionUuid);
        if ($revision->file_path && Storage::disk('public')->exists($revision->file_path))
        {
            return Storage::disk('public')->download($revision->file_path, $revision->file_name);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function render()
    {
        return view('livewire.masters.lqms.show', [
            'users' => User::all(),
        ])->title('Show LQM');
    }
}
