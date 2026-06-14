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
    public $status = 'active';
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
        $this->status = $lqm->status ?? 'active';
        $this->assigned_user_id = $lqm->assigned_user_id;

        $activeRevision = $lqm->activeRevision;
        $this->file_path = $activeRevision ? $activeRevision->file_path : null;
        $this->existing_file_name = $activeRevision ? $activeRevision->file_name : null;
        $this->revision = $activeRevision ? $activeRevision->revision : null;
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
            'status' => $this->status,
            'assigned_user_id' => $this->assigned_user_id,
            'modified_user_id' => Auth::id(),
        ];

        $lqm->update($data);

        if ($lqm->activeRevision && $this->revision) {
            $lqm->activeRevision->update(['revision' => $this->revision]);
        }
        session()->flash('success', 'LQM record updated successfully.');

        return $this->redirectRoute('masters.lqms', navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.lqms.edit', [
            'users' => User::all(),
        ])->title('Edit LQM');
    }

    public function downloadFile()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->download($this->file_path, $this->existing_file_name);
        }

        session()->flash('error', 'File not found on server.');
    }
}
