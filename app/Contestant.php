<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
    public function authored()
    {
        return $this->hasMany(Quiz::class, 'creator_id');
    }
    public function results()
    {
        return $this->hasMany(QuizResult::class, 'contestant_id');
    }
    public function aliases()
    {
        return $this->hasMany(ContestantAlias::class, 'contestant_id');
    }
}
