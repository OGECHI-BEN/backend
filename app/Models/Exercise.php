<?php

namespace App\Models;

use App\Models\Lesson;
use App\Models\UserExerciseSubmission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Exercise extends Model
{
    protected $fillable = [
        'title',
        'description',
        'instructions',
        'starter_code',
        'solution_code',
        'test_cases',
        'points',
        'difficulty',
        'is_active',
        'order',
        'lesson_id'
    ];

    protected $casts = [
        'test_cases' => 'array',
        'points' => 'integer',
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the lesson that owns the exercise
     */
    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    /**
     * Get all submissions for this exercise
     */
    public function submissions(): HasMany
    {
        return $this->hasMany(UserExerciseSubmission::class);
    }

    /**
     * Get submissions for a specific user
     */
    public function userSubmissions($userId): HasMany
    {
        return $this->submissions()->where('user_id', $userId);
    }

    /**
     * Check if user has completed this exercise
     */
    public function isCompletedByUser($userId): bool
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->where('status', 'passed')
            ->exists();
    }

    /**
     * Get the best submission for a user
     */
    public function getBestSubmissionForUser($userId)
    {
        return $this->submissions()
            ->where('user_id', $userId)
            ->orderBy('points_earned', 'desc')
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /**
     * Scope for active exercises
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordering exercises
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order', 'asc');
    }
}
