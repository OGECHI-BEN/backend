<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Level extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'order',
        'color'
    ];

    protected $casts = [
        'order' => 'integer'
    ];

    public function lessons(): HasMany
    {
        return $this->hasMany(Lesson::class);
    }
}
