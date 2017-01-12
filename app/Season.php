<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    public function quizzes()
    {
        return $this->hasMany(Quiz::class, 'season_id');
    }

    static public function current()
    {
        return Season::where('active', true)->orderBy('start', 'desc')->first();
    }
}
