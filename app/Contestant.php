<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contestant extends Model
{
    protected $fillable = [
        'creator_name',
        'name'
    ];
    public function results()
    {
        return $this->hasMany(QuizResult::class, 'contestant_id');
    }
    public function aliases()
    {
        return $this->hasMany(ContestantAlias::class, 'contestant_id');
    }
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'creator_id');
    }
    public function quizzes_raw()
    {
        return $this->hasMany(QuizRaw::class, 'creator_id');
    }
}
