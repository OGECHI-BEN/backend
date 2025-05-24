<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = [
        'lesson_id', 'title', 'description',
        'starter_code', 'solution_code',
        'test_cases', 'points', 'type', 'difficulty'
    ];

    protected $casts = [
        'test_cases' => 'array',
        'points' => 'integer'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(UserExerciseSubmission::class);
    }

    public function userProgress()
    {
        return $this->morphMany(UserProgress::class, 'progressable');
    }
}
