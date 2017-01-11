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
        return $this->belongsTo(Contestant::class, 'author_id');
    }
    public function creator()
    {
        return $this->belongsTo(User::class, 'creator_id');
    }
    public function season()
    {
        return $this->belongsTo(Season::class, 'season_id');
    }
}
