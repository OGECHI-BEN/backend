<?php

namespace App\Models;

use App\Models\Lesson;
use App\Models\UserExerciseSubmission;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;


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


    // <--- ADD THIS NEW METHOD FOR USER PROGRESS --->
    /**
     * Get all of the user's general progress for this exercise (e.g., completion status).
     */
    public function progress(): MorphMany
    {
        return $this->morphMany(UserProgress::class, 'progressable');
    }

    /**
     * Get the UserProgress record for the authenticated user specific to this question.
     * This relationship helps determine if this specific question has been completed
     * by the current user.
     */
    public function userExerciseProgress(): MorphOne
    {
        // This MorphOne relationship attempts to find a single UserProgress record
        // where:
        // 1. 'progressable_id' matches this question's ID.
        // 2. 'progressable_type' is 'App\Models\Question'.
        // 3. 'user_id' matches the ID of the currently authenticated user.
        return $this->morphOne(UserProgress::class, 'progressable')
                    ->where('user_id', Auth::id());
    }

    /**
     * Get the 'is_completed' attribute for the question.
     * This accessor checks if the authenticated user has a 'completed' UserProgress record
     * for this specific question.
     *
     * @return bool
     */
    public function getIsCompletedAttribute(): bool
    {
        // Check if the user is authenticated and if a completed progress record exists.
        // We check for `progressable_type` == `App\Models\Question` and `status` == `completed`.
        // The `userQuestionProgress` relation automatically filters by `user_id` and `progressable_type`.
        return (bool) ($this->userQuestionProgress && $this->userQuestionProgress->status === 'completed');
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
