<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::group(['middleware' => ['web']], function () {
    Route::get('/', 'LeaderboardController@redirectToCurrentSeason');
    Route::get('/all', 'LeaderboardController@displayAllSeasonsBoard');
    Route::get('/all/player-{contestant}', 'LeaderboardController@viewPlayerAllSeasons');
    Route::get('/season-{season}', 'LeaderboardController@displayLeaderboard');
    Route::get('/season-{season}/player-{contestant}', 'LeaderboardController@viewPlayer');
    Route::get('/drive', 'DriveController@listDriveContents');
    Route::get('/driveauth', 'DriveController@auth');
    Route::get('/oauth2callback', 'DriveController@authcallback');
    Route::get('/file/{file_id}', 'DriveController@singleFile');
    Route::get('/excel', 'ExcelController@showUploadForm');
    Route::post('/excel', 'ExcelController@uploadSheet');
    Route::get('/excel/edit/{quizRaw}', 'ExcelController@showEditForm');
    Route::post('/excel/edit', 'ExcelController@storeSheet');
});
Auth::routes();
Route::get('/logout', function() {
    Auth::logout();
    return redirect('/');
});
Route::get('user/activation/{token}', 'Auth\RegisterController@activateUser')->name('user.activate');

