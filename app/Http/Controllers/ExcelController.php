<?php

namespace App\Http\Controllers;

use App\Contestant;
use App\ContestantAlias;
use App\QuizRaw;
use App\QuizResult;
use App\QuizResultRaw;
use App\Season;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Storage;
use Auth;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
    { // mimetypes:application/octet-stream|
        $rules = array(
            'sheet' => 'required|file|max:200',
        );

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return redirect('/excel')->withErrors($validator);
        }
        else
        {
            $path = Storage::putFile('temp_sheets', $request->file('sheet'));
            $path = Storage::disk('local')->getAdapter()->getPathPrefix() . $path;

            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($path);
            $firstSheet = $spreadsheet->getSheet(0);
            $lastSheet = $spreadsheet->getSheet($spreadsheet->getSheetCount()-1);

            /*$out = '';
            foreach ($spreadsheet->getWorksheetIterator() as $index => $worksheet) {
                $out.= '<table>' . PHP_EOL;
                foreach ($worksheet->getRowIterator() as $row) {
                    $out.= '<tr>' . PHP_EOL;
                    $cellIterator = $row->getCellIterator();
                    $cellIterator->setIterateOnlyExistingCells(FALSE);
                    foreach ($cellIterator as $cell) {
                        $out.= '<td>' .
                            $cell->getValue() .
                            '</td>' . PHP_EOL;
                    }
                    $out.= '</tr>' . PHP_EOL;
                }
                $out.= '</table>' . PHP_EOL;
            }
            return $out;*/

            $title = $firstSheet->getCell('A1');
            $datePlayed = $firstSheet->getCell('B2');
            $dateParsed = Carbon::parse($datePlayed);
            $host = $firstSheet->getCell('B3');

            $quiz = new QuizRaw();
            $quiz->creator_name = $host;
            $quiz->name = $title;
            $quiz->date = $dateParsed->format('Y-m-d');
            $quiz->save();
            for ($i = 2; $i <= $lastSheet->getHighestRow(); $i++) {
                $result = new QuizResultRaw();
                $result->question_number = $lastSheet->getCellByColumnAndRow(1, $i);
                $result->player = $lastSheet->getCellByColumnAndRow(9, $i);
                $result->correct = ($lastSheet->getCellByColumnAndRow(11, $i) == 'Correct') ? 1 : 0;
                $result->points = $lastSheet->getCellByColumnAndRow(14, $i);
                $result->points_nostreak = $lastSheet->getCellByColumnAndRow(15, $i);
                $result->answer_time = $lastSheet->getCellByColumnAndRow(18, $i);
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
        return view('excel.edit', ['results' => $results, 'questionCount' => $questionCount, 'quiz' => $quizRaw, 'playerlist' => $playerList]);
    }

    public function storeSheet(Request $request)
    {
        $rules = array(
            'quizname' => 'required|min:4|max:255',
            'players.*.name' => 'required|min:2|max:255',
        );
        $messages = array(
            'quizname.required' => 'This quiz needs a name!',
            'quizname.min' => 'The given quiz name is way too short!',
            'quizname.max' => 'The given quiz name is WAY too long!',
            'players.*.name.required' => 'Player is missing a name!',
            'players.*.name.min' => 'A player\'s name is way too short!',
            'players.*.name.max' => 'A player\'s name is WAY too long!',
        );

        $validator = Validator::make($request->all(), $rules,  $messages);
        if ($validator->fails())
        {
            return \Redirect::back()->withErrors($validator->errors())->withInput();
        }

        $creator = Contestant::where('creator_name', $request->creator_name)->first();
        if (!$creator) {
            $creator = Contestant::where('name', ucwords($request->creator_realname))->first();
            if (!$creator) {
                $creator = Contestant::create([
                    'creator_name' => $request->creator_name,
                    'name' => ucwords($request->creator_realname),
                ]);
            } else {
                $creator->creator_name = $request->creator_name;
                $creator->save();
            }
        }

        $quiz = new \App\Quiz();
        $quiz->name = $request->quizname;
        $quiz->date = $request->quizdate;
        $quiz->creator()->associate($creator);
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
        return redirect()->action('LeaderboardController@displayLeaderboard', $quiz->season());
    }
}
