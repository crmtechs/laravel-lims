<?php

namespace App\Livewire\Masters\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Forms_Master;
use App\Models\Forms_Masters_Revision;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FormStoreRequest;
use Illuminate\Support\Facades\Auth;

class Create extends Component
{
    use WithFileUploads;

    public $document_name;
    public $document_title;
    public $description;
    public $publish_date;
    public $expiration_date;
    public $status = 'active';
    public $assigned_user_id;
    public $file_name;
    public $revision = '1';

    public function updatedFileName()
    {
        if ($this->file_name && empty($this->document_name))
        {
            $this->document_name = pathinfo($this->file_name->getClientOriginalName(), PATHINFO_FILENAME);
        }
    }

    protected function rules()
    {
        return (new FormStoreRequest())->rules();
    }

    protected function messages()
    {
        return (new FormStoreRequest())->messages();
    }

    public function mount()
    {
        $this->assigned_user_id = Auth::id();
        $this->publish_date = now()->format('Y-m-d');
    }

    public function save()
    {
        $this->validate();

        $data = [
            'document_name' => $this->document_name,
            'document_title' => $this->document_title,
            'description' => $this->description,
            'publish_date' => $this->publish_date,
            'expiration_date' => $this->expiration_date,
            'status' => $this->status,
            'assigned_user_id' => $this->assigned_user_id,
            'created_user_id' => Auth::id(),
            'modified_user_id' => Auth::id(),
        ];

        $form = Forms_Master::create($data);

        if ($this->file_name)
        {
            $directory = 'forms_masters';
            if (!Storage::disk('public')->exists($directory))
            {
                Storage::disk('public')->makeDirectory($directory);
            }
            $path = $this->file_name->store($directory, 'public');

            $revisionRecord = Forms_Masters_Revision::create([
                'forms_master_uuid' => $form->uuid,
                'change_log' => 'Document Created',
                'revision' => $this->revision,
                'file_path' => $path,
                'file_name' => $this->file_name->getClientOriginalName(),
                'file_ext' => $this->file_name->getClientOriginalExtension(),
                'file_mime_type' => $this->file_name->getMimeType(),
                'created_user_id' => Auth::id(),
            ]);

            $form->update(['forms_masters_revision_uuid' => $revisionRecord->uuid]);
        }
        session()->flash('success', 'Form record created successfully.');

        return $this->redirectRoute('masters.forms.show', ['uuid' => $form->uuid], navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.forms.create', [
            'users' => User::all(),
        ])->title('Create Form');
    }
}
