<?php

namespace App\Livewire\Masters\Annexures;

use Livewire\Component;
use App\Models\Annexures_Master;
use App\Models\Annexures_Masters_Revision;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\User;

class Show extends Component
{
    public $annexure;
    public $annexure_revisions;

    public function mount($uuid)
    {
        $this->annexure = Annexures_Master::findOrFail($uuid);
        $this->annexure_revisions = Annexures_Masters_Revision::where('annexures_master_uuid', $uuid)->orderByDesc('created_at')->get();
    }

    public function delete()
    {
        $this->annexure->delete();
        session()->flash('success', 'Annexure record deleted successfully.');
        return $this->redirectRoute('masters.annexures', navigate: true);
    }

    public function downloadFile()
    {
        $activeRevision = $this->annexure->activeRevision;
        if ($activeRevision && $activeRevision->file_path && Storage::disk('public')->exists($activeRevision->file_path))
        {
            $downloadName = $activeRevision->file_name ?? $this->annexure->document_name;
            return Storage::disk('public')->download($activeRevision->file_path, $downloadName);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function downloadRevisionFile($revisionUuid)
    {
        $revision = Annexures_Masters_Revision::findOrFail($revisionUuid);
        if ($revision->file_path && Storage::disk('public')->exists($revision->file_path))
        {
            return Storage::disk('public')->download($revision->file_path, $revision->file_name);
        }
        session()->flash('error', 'File not found on storage.');
    }

    public function render()
    {
        return view('livewire.masters.annexures.show', [
            'users' => User::all(),
        ])->title('Show Annexure');
    }
}
