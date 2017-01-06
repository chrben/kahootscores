<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    public function results()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }
    public function author()
    {
        return $this->belongsTo(Contestant::class, 'creator_id');
    }
}
