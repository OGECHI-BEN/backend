<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Add this line

class UserExerciseSubmission extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'exercise_id',
        'submission_code', // The code submitted by the user
        'status',          // E.g., 'passed', 'failed', 'submitted'
        'points_earned',   // Points earned for this specific submission
        'feedback',        // Optional: feedback on the submission
        'submitted_at',    // Timestamp for the submission
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'points_earned' => 'integer',
    ];

    /**
     * Get the user who made the submission.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the exercise this submission belongs to.
     */
    public function exercise(): BelongsTo
    {
        return $this->belongsTo(Exercise::class);
    }
}