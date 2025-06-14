<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Users extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> \*/
    use HasApiTokens, HasFactory, Notifiable;
    // use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'username',
        'email',
        'password',
        'avatar',
        'points',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'points' => 'integer',
        ];
    }

    public function progress()
    {
        return $this->hasMany(UserProgress::class);
    }


    public function exerciseSubmissions(): HasMany // New relationship for exercise submissions
    {
        return $this->hasMany(UserExerciseSubmission::class);
    }
    // If you intend to use a 'user_lesson_progress' table separately for overall lesson completion,
    // then keep this. Otherwise, lesson completion can also be tracked in UserProgress.
    public function completedLessons()
    {
        return $this->belongsToMany(Lesson::class, 'user_lesson_progress')
                    ->withPivot('is_completed', 'score')
                    ->withTimestamps();
    }


}
