<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annexures_Masters_Revision extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'annexures_masters_revisions';

    protected $primaryKey = 'uuid';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'annexures_master_uuid',
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
        return $this->belongsTo(Annexures_Master::class, 'annexures_master_uuid', 'uuid');
    }

    public function createdUser()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'uuid');
    }
}
