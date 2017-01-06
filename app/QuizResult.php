<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class QuizResult extends Model
{
    public function contestant()
    {
        return $this->belongsTo(Contestant::class, 'contestant_id');
    }
    public function alias()
    {
        return $this->hasOne(ContestantAlias::class, 'quiz_result_id');
    }
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }
}
