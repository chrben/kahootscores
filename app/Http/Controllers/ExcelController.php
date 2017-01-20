<?php

namespace App\Http\Controllers;

use App\Contestant;
use App\ContestantAlias;
use App\QuizRaw;
use App\QuizResult;
use App\QuizResultRaw;
use App\Season;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;
use Illuminate\Support\Facades\Input;
use Auth;

class ExcelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function showUploadForm(Request $request)
    {
        return view('excel.upload');
    }

    public function uploadSheet(Request $request)
    {
        $rules = array(
            'sheet' => 'required|file|mimetypes:application/octet-stream|max:200',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return redirect('/excel')->withErrors($validator);
        }
        else
        {
            $data = Excel::load($request->file('sheet'), function ($reader) {})->get();

            $quiz = new QuizRaw();
            $quiz->creator()->associate(Auth::user());
            foreach ($data->all()[0][0] as $key => $val)
            {
                $quiz->name = $key;
                break;
            }
            $quiz->save();
            foreach ($data[count($data)-1] as $answer)
            {
                $result = new QuizResultRaw();
                $result->question_number = $answer['question_number'];
                $result->player = $answer['players'];
                $result->correct = ($answer['score_points'] > 0);
                $result->points = $answer['score_points'];
                $result->points_nostreak = $answer['score_without_answer_streak_bonus_points'];
                $result->answer_time = $answer['answer_time_seconds'];
                $result->quiz()->associate($quiz);
                $result->save();
            }
        }
        return redirect()->action('ExcelController@showEditForm', $quiz->id);
    }

    public function showEditForm(Request $request, QuizRaw $quizRaw)
    {
        $results = [];
        $questionCount = 0;
        foreach($quizRaw->questions as $result)
        {
            if (!isset($results[$result->player]))
                $results[$result->player] = array("correct" => 0, "points" => 0, "points_nostreak" => 0, "total_time" => 0.0, 'best_streak' => 0, 'current_streak' => 0);
            $results[$result->player]['correct'] += ($result->correct ? 1 : 0);
            $results[$result->player]['total_time'] += $result->answer_time;

            if ($result->correct) {
                $results[$result->player]['current_streak'] += 1;
                if ($results[$result->player]['current_streak'] > $results[$result->player]['best_streak'])
                    $results[$result->player]['best_streak'] = $results[$result->player]['current_streak'];

                if (($pointsWithStreak = $result->points_nostreak + (($results[$result->player]['current_streak'] - 1) * 100)) != $result->points)
                {
                    $results[$result->player]['points'] += $pointsWithStreak;
                }
                else
                {
                    $results[$result->player]['points'] += $result->points;
                }

                $results[$result->player]['points_nostreak'] += $result->points_nostreak;
            } else {
                $results[$result->player]['current_streak'] = 0;
                $results[$result->player]['points_nostreak'] += $result->points_nostreak;
                $results[$result->player]['points'] += $result->points;
            }

            // TODO: fiks streak score estimation, sjekk drive

            $questionCount = $result->question_number > $questionCount ? $result->question_number : $questionCount;
        }
        $playerList = Contestant::pluck('name')->all();
        $seasons = Season::orderBy('start', 'desc')->get();
        return view('excel.edit', ['results' => $results, 'questionCount' => $questionCount, 'quizname' => $quizRaw->name, 'playerlist' => $playerList, 'seasons' => $seasons]);
    }

    public function storeSheet(Request $request)
    {
        $rules = array(
            'quizname' => 'required|min:4|max:255',
            'author' => 'required|min:2|max:255',
            'players.*.name' => 'required|min:2|max:255',
        );
        $messages = array(
            'quizname.required' => 'This quiz needs a name!',
            'quizname.min' => 'The given quiz name is way too short!',
            'quizname.max' => 'The given quiz name is WAY too long!',
            'author.required' => 'Author needs to be named!',
            'author.min' => 'The given author name is way too short!',
            'author.max' => 'The given author name is WAY too long!',
            'players.*.name.required' => 'Player is missing a name!',
            'players.*.name.min' => 'A player\'s name is way too short!',
            'players.*.name.max' => 'A player\'s name is WAY too long!',
        );

        $validator = Validator::make($request->all(), $rules,  $messages);
        if ($validator->fails())
        {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }
        $author = Contestant::where('name', ucwords($request->author))->first();
        if ($author == null)
        {
            $author = new Contestant();
            $author->name = ucwords($request->author);
            $author->save();
        }

        $quiz = new \App\Quiz();
        $quiz->name = $request->quizname;
        $quiz->author()->associate($author);
        $quiz->creator()->associate(Auth::user());

        if (!isset($request->season))
        {
            $season = Season::where('active', true)->orderBy('start', 'desc')->first();
        }
        else
        {
            $season = Season::find($request->season);
        }
        if ($season != null)
            $quiz->season()->associate($season);

        $quiz->question_count = $request->question_count;
        $quiz->save();
        $data = $request->all();
        foreach ($data['players'] as $alias => $playerdata)
        {
            $name = ucwords($playerdata['name']);
            $contestant = Contestant::where('name', $name)->first();
            if ($contestant == null)
            {
                $contestant = new Contestant();
                $contestant->name = $name;
                $contestant->save();
            }

            $result = new QuizResult();
            $result->score = $playerdata['score'];
            $result->correct_questions = $playerdata['correct'];
            $result->average_answer_time = $playerdata['total_time'] / $quiz->question_count;
            $result->score_nostreak = $playerdata['score_nostreak'];
            $result->best_streak = $playerdata['best_streak'];
            $result->contestant()->associate($contestant);
            $result->quiz()->associate($quiz);
            $result->save();

            $calias = new ContestantAlias();
            $calias->alias = $alias;
            $calias->contestant()->associate($contestant);
            $calias->result()->associate($result);
            $calias->save();
        }
        if ($season !== null)
            return redirect()->action('LeaderboardController@displayLeaderboard', $season);
        return redirect()->action('LeaderboardController@redirectToCurrentSeason');
    }
}
