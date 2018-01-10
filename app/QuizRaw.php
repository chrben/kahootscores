<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizRaw extends Model
{
    protected $dates = ['date'];
    public function questions()
    {
        return $this->hasMany(QuizResultRaw::class, 'quiz_id');
    }
    public function creator()
    {
        return $this->belongsTo(Contestant::class, 'creator_id');
    }
}
