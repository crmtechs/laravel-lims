# Implementing LQM Masters Revision System

The objective is to refactor the LQM Masters module to support a versioned revision system for documents. We will move file metadata out of the `lqms_masters` table into a new `lqms_masters_revisions` table, establishing a one-to-many relationship, and keep track of the active revision directly on the master record.

## User Review Required

> [!WARNING]
> This requires a database schema change that drops columns (`file_name`, `file_ext`, `file_mime_type`, and `file_path`) from the existing `lqms_masters` table.

> [!IMPORTANT]
> The relationship relies on UUIDs. You requested `lqms_masters_revision_id`. To maintain consistency with the UUID primary keys, I plan to name this column `lqms_masters_revision_uuid` in the database. Please let me know if you strictly prefer `_id`.

## Open Questions

1. **Existing Data**: Do we need to write a script inside the migration to migrate existing files currently sitting in `lqms_masters` over to the new `lqms_masters_revisions` table? Or is it safe to just drop the old columns and start fresh?

- it is safe to drop, this is just a development copy

2. **`file_path` Column**: You mentioned moving `file_name`, `file_ext`, and `file_mime_type`. I assume `file_path` must also move to the revisions table so each revision retains its unique physical file. Is this correct?

- yes, file_path is also needed

3. **Editing Behavior**: On the Edit page, if a user browses and uploads a _new_ file, should that automatically generate Revision 2, Revision 3, etc., and update the active pointer on the master?

- active pointer moved to latest revision, but revision id should not be generated automatically, user will manually enter on revision create screen.

4. **Tracking Users**: Should the `lqms_masters_revisions` table also have `created_user_id` so we know exactly _who_ uploaded each specific revision?

- yes

## Proposed Changes

---

### Database Layer

#### [NEW] `database/migrations/xxxx_xx_xx_xxxxxx_create_lqms_masters_revisions_table.php`

- Create the `lqms_masters_revisions` table with columns: `uuid` (PK), `lqms_master_uuid` (FK), `revision` (integer), `file_path`, `file_name`, `file_ext`, `file_mime_type`, `created_user_id`, timestamps, and soft deletes.

#### [NEW] `database/migrations/xxxx_xx_xx_xxxxxx_update_lqms_masters_for_revisions.php`

- Add `lqms_masters_revision_uuid` to `lqms_masters`.
- Drop `file_path`, `file_name`, `file_ext`, and `file_mime_type` from `lqms_masters`.

#### [NEW] `app/Models/LQMs_Masters_Revision.php`

- Create the new model with `HasUuids` and `SoftDeletes`.
- Define `fillable` properties and `belongsTo` relationship to `LQMs_Master`.

#### [MODIFY] `app/Models/LQMs_Master.php`

- Update `$fillable` to remove file fields and add `lqms_masters_revision_uuid`.
- Add `activeRevision()` (belongsTo) and `revisions()` (hasMany) relationships.

---

### Application Logic

#### [MODIFY] `app/Livewire/Masters/Lqms/Create.php`

- After successfully inserting into `LQMs_Master`, if a file was uploaded, we will:
    - Create a new row in `LQMs_Masters_Revision` with `revision = 1`.
    - Update the freshly created `LQMs_Master` record so `lqms_masters_revision_uuid` points to this new revision.

#### [MODIFY] `app/Livewire/Masters/Lqms/Edit.php`

- Update the component to pull the current file data from `$lqm->activeRevision`.
- If a new file is uploaded during an edit, we determine the next revision number (e.g., `max(revision) + 1`), insert a new `LQMs_Masters_Revision`, and update the master's active pointer.

#### [MODIFY] `app/Livewire/Masters/Lqms/Show.php`

- Update properties to pull `file_path` and `file_name` from `$this->lqm->activeRevision`.
- Update the `downloadFile()` method to serve the file from the active revision.

---

### UI Views

#### [MODIFY] `resources/views/livewire/masters/lqms/form.blade.php`

- Update the "Current file" display logic to read from the active revision object instead of root model properties.

#### [MODIFY] `resources/views/livewire/masters/lqms/show.blade.php`

- Update the UI to read the `file_name` and download link from the revision logic.

## Verification Plan

### Automated / Manual Testing

- Run all new migrations.
- **Create**: Fill the form, upload a file, and verify _both_ the `lqms_masters` and `lqms_masters_revisions` tables are populated correctly, with the master pointing to the revision.
- **Edit**: Upload a new file on an existing LQM and verify a _second_ revision is created and the master pointer is updated.
- **Show**: Ensure the detail page displays the correct file name and downloads the latest revision successfully.
