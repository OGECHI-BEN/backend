<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'title', 'slug', 'description', 'difficulty',
        'estimated_time', 'lessons_count', 'exercises_count',
        'projects_count', 'rating'
    ];

    protected $casts = [
        'estimated_time' => 'integer',
        'rating' => 'float'
    ];

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function userProgress()
    {
        return $this->morphMany(UserProgress::class, 'progressable');
    }

    public function getLessonsCountAttribute()
    {
        return $this->modules->sum(function ($module) {
            return $module->lessons->count();
        });
    }

    public function getExercisesCountAttribute()
    {
        return $this->modules->sum(function ($module) {
            return $module->lessons->sum(function ($lesson) {
                return $lesson->exercises->count();
            });
        });
    }

    public function getProjectsCountAttribute()
    {
        return $this->modules->sum(function ($module) {
            return $module->lessons->sum(function ($lesson) {
                return $lesson->exercises->where('type', 'project')->count();
            });
        });
    }
}
