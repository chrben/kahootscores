<?php

namespace App\Http\Controllers;

use App\Contestant;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Season;


class LeaderboardController extends Controller
{
    public function redirectToCurrentSeason(Request $request)
    {
        $season = Season::current();
        if ($season !== null)
            return \Redirect::action('LeaderboardController@displayLeaderboard', ['season' => $season]);
        return \Redirect::action('LeaderboardController@displayAllSeasonsBoard');
    }
    public function displayLeaderboard(Request $request, Season $season = null)
    {
        $leaderboard = $this->getLeaderboard($season);
        return view('leaderboards.display', ['leaderboard' => $leaderboard, 'season' => $season]);
    }
    public function displayAllSeasonsBoard(Request $request)
    {
        return $this->displayLeaderboard($request);
    }

    public function viewPlayerAllSeasons(Request $request, Contestant $contestant)
    {
        return $this->viewPlayer($request, null, $contestant);
    }

    public function viewPlayer(Request $request, $season, Contestant $contestant)
    {
        $season = ($season===null ? null : Season::find($season));
        $accuracyData = $this->getAccuracyLeaderboard($season);
        $i = 0;
        if (count($accuracyData) == 0)
        {
            return view('leaderboards.player', [
                'contestant' => $contestant,
                'dataMissing' => true,
                'aliases' => $contestant->aliases->pluck('alias')->all(),
                'season' => $season,
            ]);
        }
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

        $scoreData = $this->getLeaderboard($season);
        $i = 0;
        if (count($scoreData) == 0)
        {
            return view('leaderboards.player', [
                'contestant' => $contestant,
                'dataMissing' => true,
                'aliases' => $contestant->aliases->pluck('alias')->all(),
                'season' => $season,
            ]);
        }
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

        $streakData = $this->getStreakLeaderboard($season);
        $i = 0;
        if (count($streakData) == 0)
        {
            return view('leaderboards.player', [
                'contestant' => $contestant,
                'dataMissing' => true,
                'aliases' => $contestant->aliases->pluck('alias')->all(),
                'season' => $season,
            ]);
        }
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

        $ASPAData = $this->getASPALeaderboard($season);
        $i = 0;
        if (count($ASPAData) == 0)
        {
            return view('leaderboards.player', [
                'contestant' => $contestant,
                'dataMissing' => true,
                'aliases' => $contestant->aliases->pluck('alias')->all(),
                'season' => $season,
            ]);
        }
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
            'aliases' => $contestant->aliases->unique('alias')->pluck('alias')->all(),
            'season' => $season,
        ]);
    }
    private function getStreakLeaderboard(Season $season = null)
    {
        if ($season !== null)
        {
            return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
                ->leftJoin('quizzes', 'quiz_results.quiz_id', '=', 'quizzes.id')
                ->selectRaw('contestants.name, contestants.id, max(quiz_results.best_streak) as streak')
                ->whereBetween('quizzes.date', [$season->start, ($season->end ? $season->end : Carbon::now())])
                ->groupBy('contestants.id')
                ->orderBy('streak', 'desc')
                ->get();
        }
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->selectRaw('contestants.name, contestants.id, max(quiz_results.best_streak) as streak')
            ->groupBy('contestants.id')
            ->orderBy('streak', 'desc')
            ->get();
    }
    private function getLeaderboard(Season $season = null)
    {
        if ($season !== null)
        {
            return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
                ->leftJoin('quizzes', 'quiz_results.quiz_id', '=', 'quizzes.id')
                ->selectRaw('contestants.name, contestants.id, sum(quiz_results.score) as score')
                ->whereBetween('quizzes.date', [$season->start, ($season->end ? $season->end : Carbon::now())])
                ->groupBy('contestants.id')
                ->orderBy('score', 'desc')
                ->get();
        }
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->selectRaw('contestants.name, contestants.id, sum(quiz_results.score) as score')
            ->groupBy('contestants.id')
            ->orderBy('score', 'desc')
            ->get();
    }
    private function getASPALeaderboard(Season $season = null)
    {
        if ($season !== null)
        {
            return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
                ->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_results.quiz_id')
                ->selectRaw('contestants.name, contestants.id, (sum(quiz_results.score)/sum(quizzes.question_count)) as ASPA')
                ->whereBetween('quizzes.date', [$season->start, ($season->end ? $season->end : Carbon::now())])
                ->groupBy('contestants.id')
                ->orderBy('ASPA', 'desc')
                ->get();
        }
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_results.quiz_id')
            ->selectRaw('contestants.name, contestants.id, (sum(quiz_results.score)/sum(quizzes.question_count)) as ASPA')
            ->groupBy('contestants.id')
            ->orderBy('ASPA', 'desc')
            ->get();
    }

    private function getAccuracyLeaderboard(Season $season = null)
    {
        if ($season !== null)
        {
            return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
                ->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_results.quiz_id')
                ->selectRaw('contestants.name, contestants.id, (sum(quiz_results.correct_questions)/sum(quizzes.question_count)) as accuracy')
                ->whereBetween('quizzes.date', [$season->start, ($season->end ? $season->end : Carbon::now())])
                ->groupBy('contestants.id')
                ->orderBy('accuracy', 'desc')
                ->get();
        }
        return \App\Contestant::leftJoin('quiz_results', 'quiz_results.contestant_id', '=', 'contestants.id')
            ->leftJoin('quizzes', 'quizzes.id', '=', 'quiz_results.quiz_id')
            ->selectRaw('contestants.name, contestants.id, (sum(quiz_results.correct_questions)/sum(quizzes.question_count)) as accuracy')
            ->groupBy('contestants.id')
            ->orderBy('accuracy', 'desc')
            ->get();
    }
}

