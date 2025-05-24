<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserProgress extends Model
{
    protected $fillable = [
        'user_id',
        'progressable_id',
        'progressable_type',
        'status',
        'score',
        'completed_at',
        'last_accessed_at'
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'score' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function progressable()
    {
        return $this->morphTo();
    }
}
