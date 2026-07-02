<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class LQMs_Masters_Revision extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'lqms_masters_revisions';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'lqms_master_uuid',
        'change_log',
        'revision',
        'file_path',
        'file_name',
        'file_ext',
        'file_mime_type',
        'created_user_id',
    ];

    public function master()
    {
        return $this->belongsTo(LQMs_Master::class, 'lqms_master_uuid', 'uuid');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'uuid');
    }
}
