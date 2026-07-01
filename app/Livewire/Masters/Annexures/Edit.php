<?php

namespace App\Livewire\Masters\Annexures;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Annexures_Master;
use App\Models\Annexures_Masters_Revision;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\AnnexureUpdateRequest;
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
        return (new AnnexureUpdateRequest())->rules();
    }

    protected function messages()
    {
        return (new AnnexureUpdateRequest())->messages();
    }

    public function mount($uuid)
    {
        $this->uuid = $uuid;
        $annexure = Annexures_Master::findOrFail($uuid);

        $this->document_name = $annexure->document_name;
        $this->document_title = $annexure->document_title;
        $this->description = $annexure->description;
        $this->publish_date = $annexure->publish_date ? $annexure->publish_date->format('Y-m-d') : null;
        $this->expiration_date = $annexure->expiration_date ? $annexure->expiration_date->format('Y-m-d') : null;
        $this->status = $annexure->status ?? 'active';
        $this->assigned_user_id = $annexure->assigned_user_id;

        $activeRevision = $annexure->activeRevision;
        $this->file_path = $activeRevision ? $activeRevision->file_path : null;
        $this->existing_file_name = $activeRevision ? $activeRevision->file_name : null;
        $this->revision = $activeRevision ? $activeRevision->revision : null;
    }

    public function save()
    {
        $this->validate();

        $annexure = Annexures_Master::findOrFail($this->uuid);

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

        $annexure->update($data);

        if ($annexure->activeRevision && $this->revision) {
            $annexure->activeRevision->update(['revision' => $this->revision]);
        }
        session()->flash('success', 'Annexure record updated successfully.');

        return $this->redirectRoute('masters.annexures', navigate: true);
    }

    public function render()
    {
        return view('livewire.masters.annexures.edit', [
            'users' => User::all(),
        ])->title('Edit Annexure');
    }

    public function downloadFile()
    {
        if ($this->file_path && Storage::disk('public')->exists($this->file_path)) {
            return Storage::disk('public')->download($this->file_path, $this->existing_file_name);
        }

        session()->flash('error', 'File not found on server.');
    }
}
