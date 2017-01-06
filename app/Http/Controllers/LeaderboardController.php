<?php

namespace App\Http\Controllers;

use App\Contestant;
use Illuminate\Http\Request;

class LeaderboardController extends Controller
{
    public function displayLeaderboard(Request $request)
    {
        $leaderboard = $this->getLeaderboard();
        return view('leaderboards.display', ['leaderboard' => $leaderboard]);
    }

    public function viewPlayer(Request $request, Contestant $contestant)
    {
        $accuracyData = $this->getAccuracyLeaderboard();
        $i = 0;
        foreach ($accuracyData as $entry) {
            $i++;
            $entry->placement = $i;
        }
        $accuracy = [
            "best" => $accuracyData->where('accuracy', '!=', null)->first()->toArray(),
            "worst" => $accuracyData->where('accuracy', '!=', null)->last()->toArray(),
            "yours" => $accuracyData->where('id', $contestant->id)->first()->toArray(),
            "count" => $accuracyData->where('accuracy', '!=', null)->count(),
        ];

        $scoreData = $this->getLeaderboard();
        $i = 0;
        foreach ($scoreData as $entry) {
            $i++;
            $entry->placement = $i;
        }
        $score = [
            "best" => $scoreData->where('score', '!=', null)->first()->toArray(),
            "worst" => $scoreData->where('score', '!=', null)->last()->toArray(),
            "yours" => $scoreData->where('id', $contestant->id)->first()->toArray(),
            "count" => $scoreData->where('score', '!=', null)->count(),
        ];

        $streakData = $this->getStreakLeaderboard();
        $i = 0;
        foreach ($streakData as $entry) {
            $i++;
            $entry->placement = $i;
        }
        $streaks = [
            "best" => $streakData->where('streak', '!=', null)->first()->toArray(),
            "worst" => $streakData->where('streak', '!=', null)->last()->toArray(),
            "yours" => $streakData->where('id', $contestant->id)->first()->toArray(),
            "count" => $streakData->where('streak', '!=', null)->count(),
        ];

        $ASPAData = $this->getASPALeaderboard();
        $i = 0;
        foreach ($ASPAData as $entry) {
            $i++;
            $entry->placement = $i;
        }
        $aspa = [
            "best" => $ASPAData->where('ASPA', '!=', null)->first()->toArray(),
            "worst" => $ASPAData->where('ASPA', '!=', null)->last()->toArray(),
            "yours" => $ASPAData->where('id', $contestant->id)->first()->toArray(),
            "count" => $ASPAData->where('ASPA', '!=', null)->count(),
        ];

        return view('leaderboards.player', [
            'contestant' => $contestant,
            'streaks' => $streaks,
            'aspa' => $aspa,
            'score' => $score,
            'accuracy' => $accuracy,
            'aliases' => $contestant->aliases->pluck('alias')->all(),
        ]);
    }
    private function getStreakLeaderboard()
    {
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->selectRaw('contestants.name, contestants.id, max(quiz_results.best_streak) as streak')
            ->groupBy('contestants.id')
            ->orderBy('streak', 'desc')
            ->get();
    }
    private function getLeaderboard()
    {
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->selectRaw('contestants.name, contestants.id, sum(quiz_results.score) as score')
            ->groupBy('contestants.id')
            ->orderBy('score', 'desc')
            ->get();
    }
    private function getASPALeaderboard()
    {
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->selectRaw('contestants.name, contestants.id, (sum(quiz_results.score)/sum(quiz_results.correct_questions)) as ASPA')
            ->groupBy('contestants.id')
            ->orderBy('ASPA', 'desc')
            ->get();
    }

    private function getAccuracyLeaderboard()
    {
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_results.quiz_id')
            ->selectRaw('contestants.name, contestants.id, (sum(quiz_results.correct_questions)/sum(quizzes.question_count)) as accuracy')
            ->groupBy('contestants.id')
            ->orderBy('accuracy', 'desc')
            ->get();
    }
}

