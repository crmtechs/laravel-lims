<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\SoftDeletes;

class Annexures_Master extends Model
{
    use HasUuids, SoftDeletes;

    protected $table = 'annexures_masters';

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
        'annexures_masters_revision_uuid',
        'created_user_id',
        'modified_user_id',
    ];

    public function getPerPage()
    {
        return config('app.pagination_limit', 10);
    }

    protected $casts = [
        'publish_date' => 'date',
        'expiration_date' => 'date',
    ];

    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_user_id', 'uuid');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_user_id', 'uuid');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'modified_user_id', 'uuid');
    }

    public function activeRevision()
    {
        return $this->belongsTo(Annexures_Masters_Revision::class, 'annexures_masters_revision_uuid', 'uuid');
    }

    public function revisions()
    {
        return $this->hasMany(Annexures_Masters_Revision::class, 'annexures_master_uuid', 'uuid');
    }

    protected static function booted()
    {
        static::deleting(function ($model) {
            $model->revisions()->delete();
        });
    }
}
