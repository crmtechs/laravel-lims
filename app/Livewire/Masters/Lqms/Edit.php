<?php

namespace App\Livewire\Masters\Lqms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\LQMs_Master;
use App\Models\LQMs_Masters_Revision;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\LQMUpdateRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class Edit extends Component
{
    use WithFileUploads;

    public $uuid;
    public $document_name;
    public $document_title;
    public $description;
    public $publish_date;
    public $expiration_date;
    public $status_id = 'Active';
    public $assigned_user_id;
    public $file_name;
    public $file_path;
    public $existing_file_name;
    public $revision;

    protected function rules()
    {
        return (new LQMUpdateRequest())->rules();
    }

    protected function messages()
    {
        return (new LQMUpdateRequest())->messages();
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $lqm = LQMs_Master::findOrFail($uuid);

        $this->document_name = $lqm->document_name;
        $this->document_title = $lqm->document_title;
        $this->description = $lqm->description;
        $this->publish_date = $lqm->publish_date ? $lqm->publish_date->format('Y-m-d') : null;
        $this->expiration_date = $lqm->expiration_date ? $lqm->expiration_date->format('Y-m-d') : null;
        $this->status_id = $lqm->status_id ?? 'Active';
        $this->assigned_user_id = $lqm->assigned_user_id;
        
        $activeRevision = $lqm->activeRevision;
        $this->file_path = $activeRevision ? $activeRevision->file_path : null;
        $this->existing_file_name = $activeRevision ? $activeRevision->file_name : null;
    }

    public function save()
    {
        $this->validate();

        $lqm = LQMs_Master::findOrFail($this->uuid);

        $data = [
            'document_name' => $this->document_name,
            'document_title' => $this->document_title,
            'description' => $this->description,
            'publish_date' => $this->publish_date,
            'expiration_date' => $this->expiration_date,
            'status_id' => $this->status_id,
            'assigned_user_id' => $this->assigned_user_id,
            'modified_user_id' => Auth::id(),
        ];

        if ($this->file_name)
        {
            $directory = 'lqms_masters';
            if (!Storage::disk('public')->exists($directory))
            {
                Storage::disk('public')->makeDirectory($directory);
            }
            $path = $this->file_name->store($directory, 'public');
            
            $revisionRecord = LQMs_Masters_Revision::create([
                'lqms_master_uuid' => $lqm->uuid,
                'revision' => $this->revision,
                'file_path' => $path,
                'file_name' => $this->file_name->getClientOriginalName(),
                'file_ext' => $this->file_name->getClientOriginalExtension(),
                'file_mime_type' => $this->file_name->getMimeType(),
                'created_user_id' => Auth::id(),
            ]);

            $data['lqms_masters_revision_uuid'] = $revisionRecord->uuid;
        }

        $lqm->update($data);
        session()->flash('success', 'LQM record updated successfully.');

        return $this->redirectRoute('masters.lqms', navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.lqms.edit', [
            'users' => User::all(),
        ])->title('Edit LQM');
    }
}
