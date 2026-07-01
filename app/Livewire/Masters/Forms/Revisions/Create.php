<?php

namespace App\Livewire\Masters\Forms\Revisions;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Forms_Master;
use App\Models\Forms_Masters_Revision;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\FormRevisionCreateRequest;

class Create extends Component
{
    use WithFileUploads;

    public $uuid;
    public $form;
    
    public $file_name;
    public $revision;
    public $change_log;

    protected function rules()
    {
        return (new FormRevisionCreateRequest())->rules();
    }

    protected function messages()
    {
        return (new FormRevisionCreateRequest())->messages();
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $this->form = Forms_Master::findOrFail($uuid);
    }

    public function save()
    {
        $this->validate();

        $directory = 'forms_masters';
        if (!Storage::disk('public')->exists($directory))
        {
            Storage::disk('public')->makeDirectory($directory);
        }
        
        $path = $this->file_name->store($directory, 'public');

        $revisionRecord = Forms_Masters_Revision::create([
            'forms_master_uuid' => $this->form->uuid,
            'revision' => $this->revision,
            'change_log' => $this->change_log,
            'file_path' => $path,
            'file_name' => $this->file_name->getClientOriginalName(),
            'file_ext' => $this->file_name->getClientOriginalExtension(),
            'file_mime_type' => $this->file_name->getMimeType(),
            'created_user_id' => Auth::id(),
        ]);

        $this->form->update([
            'forms_masters_revision_uuid' => $revisionRecord->uuid,
            'modified_user_id' => Auth::id(),
        ]);

        session()->flash('success', 'New revision created successfully.');

        return $this->redirectRoute('masters.forms.show', $this->form->uuid, navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.forms.revisions.create')->title('Create Revision');
    }
}
