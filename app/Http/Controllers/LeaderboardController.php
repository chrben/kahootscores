<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function displayLeaderboard(Request $request)
    {
        $leaderboard = \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
                ->selectRaw('contestants.name, contestants.id, sum(quiz_results.score) as score')
                ->groupBy('contestants.id')
                ->orderBy('score', 'desc')
                ->get();
        return view('leaderboards.display', ['leaderboard' => $leaderboard]);
    }

    public function viewPlayer(Request $request, Contestant $contestant)
    {
        if ($contestant->results->count() != 0)
        {
            $question_sum = $contestant->results()->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_results.quiz_id')->selectRaw('sum(quizzes.question_count) as qcount')->groupBy('contestant_id')->first()->qcount;
            $correct_sum = $contestant->results->sum('correct_questions');
            $correct_pct = $correct_sum/$question_sum;
        }
        else{
            $question_sum = 0;
            $correct_sum = 0;
            $correct_pct = 0.0;
        }
        return view('leaderboards.player', ['contestant' => $contestant, 'correct_pct' => $correct_pct, 'correct_sum' => $correct_sum, 'question_sum' => $question_sum]);
    }
}

