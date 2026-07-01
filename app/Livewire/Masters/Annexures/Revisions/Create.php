<?php

namespace App\Livewire\Masters\Annexures\Revisions;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Annexures_Master;
use App\Models\Annexures_Masters_Revision;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\AnnexureRevisionCreateRequest;

class Create extends Component
{
    use WithFileUploads;

    public $uuid;
    public $annexure;
    
    public $file_name;
    public $revision;
    public $change_log;

    protected function rules()
    {
        return (new AnnexureRevisionCreateRequest())->rules();
    }

    protected function messages()
    {
        return (new AnnexureRevisionCreateRequest())->messages();
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->annexure = Annexures_Master::findOrFail($uuid);
    }

    public function save()
    {
        $this->validate();

        $directory = 'annexures_masters';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        $path = $this->file_name->store($directory, 'public');

        $revisionRecord = Annexures_Masters_Revision::create([
            'annexures_master_uuid' => $this->annexure->uuid,
            'revision' => $this->revision,
            'change_log' => $this->change_log,
            'file_path' => $path,
            'file_name' => $this->file_name->getClientOriginalName(),
            'file_ext' => $this->file_name->getClientOriginalExtension(),
            'file_mime_type' => $this->file_name->getMimeType(),
            'created_user_id' => Auth::id(),
        ]);

        $this->annexure->update([
            'annexures_masters_revision_uuid' => $revisionRecord->uuid,
            'modified_user_id' => Auth::id(),
        ]);

        session()->flash('success', 'New revision created successfully.');

        return $this->redirectRoute('masters.annexures.show', $this->annexure->uuid, navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.annexures.revisions.create')->title('Create Revision');
    }
}
