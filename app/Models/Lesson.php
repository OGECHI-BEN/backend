<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    //
    protected $fillable = [
        'module_id', 'title', 'content',
        'duration', 'order_index'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }

    public function userProgress()
    {
        return $this->hasMany(UserProgress::class);
    }
}
