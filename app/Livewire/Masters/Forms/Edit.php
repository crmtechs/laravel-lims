<?php

namespace App\Livewire\Masters\Forms;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Forms_Master;
use App\Models\Forms_Masters_Revision;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\FormUpdateRequest;
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
        return (new FormUpdateRequest())->rules();
    }

    protected function messages()
    {
        return (new FormUpdateRequest())->messages();
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $form = Forms_Master::findOrFail($uuid);

        $this->document_name = $form->document_name;
        $this->document_title = $form->document_title;
        $this->description = $form->description;
        $this->publish_date = $form->publish_date ? $form->publish_date->format('Y-m-d') : null;
        $this->expiration_date = $form->expiration_date ? $form->expiration_date->format('Y-m-d') : null;
        $this->status = $form->status ?? 'active';
        $this->assigned_user_id = $form->assigned_user_id;

        $activeRevision = $form->activeRevision;
        $this->file_path = $activeRevision ? $activeRevision->file_path : null;
        $this->existing_file_name = $activeRevision ? $activeRevision->file_name : null;
        $this->revision = $activeRevision ? $activeRevision->revision : null;
    }

    public function save()
    {
        $this->validate();

        $form = Forms_Master::findOrFail($this->uuid);

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

        $form->update($data);

        if ($form->activeRevision && $this->revision)
        {
            $form->activeRevision->update(['revision' => $this->revision]);
        }
        session()->flash('success', 'Form record updated successfully.');

        return $this->redirectRoute('masters.forms', navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.forms.edit', [
            'users' => User::all(),
        ])->title('Edit Form');
    }

    public function downloadFile()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path))
        {
            return Storage::disk('public')->download($this->file_path, $this->existing_file_name);
        }

        session()->flash('error', 'File not found on server.');
    }
}
