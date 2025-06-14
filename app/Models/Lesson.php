<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany; // This is crucial for polymorphic relations

class Lesson extends Model
{
    //

    protected $fillable = [
        'language_id',
        'level_id',
        'title',
        'slug',
        'content',
        'order',
        'estimated_time',
        'is_published'
    ];

    protected $casts = [
        'order' => 'integer',
        'estimated_time' => 'integer',
        'is_published' => 'boolean'
    ];

    public function language(): BelongsTo
    {
        return $this->belongsTo(Language::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    /**
     * Get all of the user's progress for this lesson via polymorphic relationship.
     */
    public function progress(): MorphMany
    {
        return $this->morphMany(UserProgress::class, 'progressable');
    }
}
