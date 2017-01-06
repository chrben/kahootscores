<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizRaw extends Model
{
    public function questions()
    {
        return $this->hasMany(QuizResultRaw::class, 'quiz_id');
    }
}
