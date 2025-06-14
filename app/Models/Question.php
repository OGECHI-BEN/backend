<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;

class Question extends Model
{
    protected $fillable = [
        'lesson_id',
        'question',
        'type',
        'question_text',
        'options',
        'correct_answer',
        'explanation',
        'points',
        'difficulty'
    ];

    protected $casts = [
        'options' => 'array',
        'points' => 'integer',
        'difficulty' => 'integer'
    ];

    public function lesson(): BelongsTo
    {
        return $this->belongsTo(Lesson::class);
    }

    public function progress(): MorphMany
    {
        return $this->morphMany(UserProgress::class, 'progressable');
    }

    /**
     * Get the UserProgress record for the authenticated user specific to this question.
     * This relationship helps determine if this specific question has been completed
     * by the current user.
     */
    public function userQuestionProgress(): MorphOne
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

}
