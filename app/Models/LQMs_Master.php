<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class LQMs_Master extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'lqms_masters';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'document_name',
        'document_title',
        'description',
        'publish_date',
        'expiration_date',
        'status',
        'assigned_user_id',
        'lqms_masters_revision_uuid',
        'created_user_id',
        'modified_user_id',
    ];

    protected $casts = [
        'publish_date' => 'date',
        'expiration_date' => 'date',
    ];

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'uuid');
    }

    public function activeRevision()
    {
        return $this->belongsTo(LQMs_Masters_Revision::class, 'lqms_masters_revision_uuid', 'uuid');
    }

    public function revisions()
    {
        return $this->hasMany(LQMs_Masters_Revision::class, 'lqms_master_uuid', 'uuid');
    }
}
