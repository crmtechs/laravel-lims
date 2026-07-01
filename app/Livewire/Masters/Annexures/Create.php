<?php

namespace App\Livewire\Masters\Annexures;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Annexures_Master;
use App\Models\Annexures_Masters_Revision;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AnnexureStoreRequest;
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
        if ($this->file_name && empty($this->document_name)) {
            $this->document_name = pathinfo($this->file_name->getClientOriginalName(), PATHINFO_FILENAME);
        }
    }

    protected function rules()
    {
        return (new AnnexureStoreRequest())->rules();
    }

    protected function messages()
    {
        return (new AnnexureStoreRequest())->messages();
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

        $annexure = Annexures_Master::create($data);

        if ($this->file_name)
        {
            $directory = 'annexures_masters';
            if (!Storage::disk('public')->exists($directory))
            {
                Storage::disk('public')->makeDirectory($directory);
            }
            $path = $this->file_name->store($directory, 'public');

            $revisionRecord = Annexures_Masters_Revision::create([
                'annexures_master_uuid' => $annexure->uuid,
                'change_log' => 'Document Created',
                'revision' => $this->revision,
                'file_path' => $path,
                'file_name' => $this->file_name->getClientOriginalName(),
                'file_ext' => $this->file_name->getClientOriginalExtension(),
                'file_mime_type' => $this->file_name->getMimeType(),
                'created_user_id' => Auth::id(),
            ]);

            $annexure->update(['annexures_masters_revision_uuid' => $revisionRecord->uuid]);
        }
        session()->flash('success', 'Annexure record created successfully.');

        return $this->redirectRoute('masters.annexures.show', ['uuid' => $annexure->uuid], navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.annexures.create', [
            'users' => User::all(),
        ])->title('Create Annexure');
    }
}
