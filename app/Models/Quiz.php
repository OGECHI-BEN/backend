<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $fillable = [
        'language_id',
        'level_id',
        'title',
        'description',
        'points',
        'passing_score'
    ];

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function level()
    {
        return $this->belongsTo(Level::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function getTotalPoints()
    {
        return $this->questions()->sum('points');
    }
}
