<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $dates = ['date'];
    public function results()
    {
        return $this->hasMany(QuizResult::class, 'quiz_id');
    }
    public function creator()
    {
        return $this->belongsTo(Contestant::class, 'creator_id');
    }
    public function season()
    {
        return Season::findQuiz($this);
    }
}
