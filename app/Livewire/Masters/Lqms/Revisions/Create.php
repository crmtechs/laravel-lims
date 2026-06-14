<?php

namespace App\Livewire\Masters\Lqms\Revisions;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LQMs_Master;
use App\Models\LQMs_Masters_Revision;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LQMRevisionCreateRequest;

class Create extends Component
{
    use WithFileUploads;

    public $uuid;
    public $lqm;
    
    public $file_name;
    public $revision;
    public $change_log;

    protected function rules()
    {
        return (new LQMRevisionCreateRequest())->rules();
    }

    protected function messages()
    {
        return (new LQMRevisionCreateRequest())->messages();
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->lqm = LQMs_Master::findOrFail($uuid);
    }

    public function save()
    {
        $this->validate();

        $directory = 'lqms_masters';
        if (!Storage::disk('public')->exists($directory)) {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        $path = $this->file_name->store($directory, 'public');

        $revisionRecord = LQMs_Masters_Revision::create([
            'lqms_master_uuid' => $this->lqm->uuid,
            'revision' => $this->revision,
            'change_log' => $this->change_log,
            'file_path' => $path,
            'file_name' => $this->file_name->getClientOriginalName(),
            'file_ext' => $this->file_name->getClientOriginalExtension(),
            'file_mime_type' => $this->file_name->getMimeType(),
            'created_user_id' => Auth::id(),
        ]);

        $this->lqm->update([
            'lqms_masters_revision_uuid' => $revisionRecord->uuid,
            'modified_user_id' => Auth::id(),
        ]);

        session()->flash('success', 'New revision created successfully.');

        return $this->redirectRoute('masters.lqms.show', $this->lqm->uuid, navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.lqms.revisions.create')->title('Create Revision');
    }
}
