<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Season extends Model
{
    protected $dates = ['start', 'end'];

    public function quizzes()
    {
        if ($this->end == null) {
            return Quiz::whereBetween('date', [$this->start->format('Y-m-d'), Carbon::now()->format('Y-m-d')])->all();
        } else {
            return Quiz::whereBetween('date', [$this->start->format('Y-m-d'), $this->end->format('Y-m-d')])->all();
        }
    }

    static public function current()
    {
        return Season::where('end', null)->orderBy('start', 'desc')->first();
    }
    static public function findQuiz(Quiz $quiz)
    {
        $seasons = Season::all();
        foreach ($seasons as $season)
        {
            if ($season->end != null) {
                if ($quiz->date->between($season->start, $season->end))
                    return $season;
            } else {
                if ($quiz->date->between($season->start, Carbon::now()))
                    return $season;
            }
        }
        return null;
    }
}
