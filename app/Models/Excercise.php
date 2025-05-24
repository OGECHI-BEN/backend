<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Excercise extends Model
{
    //
    protected $fillable = [
        'lesson_id', 'title', 'description',
        'starter_code', 'solution_code',
        'test_cases', 'points'
    ];

    protected $casts = [
        'test_cases' => 'array'
    ];

    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }

    public function submissions()
    {
        return $this->hasMany(UserExerciseSubmission::class);
    }
}
