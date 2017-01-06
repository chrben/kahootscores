<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizResultRaw extends Model
{
    public function quiz()
    {
        return $this->belongsTo(QuizRaw::class, 'quiz_id');
    }
}
