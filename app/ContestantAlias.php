<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestantAlias extends Model
{
    public function contestant()
    {
        return $this->belongsTo(Contestant::class, 'contestant_id');
    }
    public function result()
    {
        return $this->belongsTo(QuizResult::class, 'quiz_result_id');
    }
}
