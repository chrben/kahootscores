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
Route::get('/', 'LeaderboardController@displayLeaderboard');
Route::get('/player/{contestant}', 'LeaderboardController@viewPlayer');
Route::get('/drive', 'DriveController@listDriveContents');
Route::get('/driveauth', 'DriveController@auth');
Route::get('/oauth2callback', 'DriveController@authcallback');
Route::get('/file/{file_id}', 'DriveController@singleFile');
Route::get('/excel', 'ExcelController@showUploadForm');
Route::post('/excel', 'ExcelController@uploadSheet');
Route::get('/excel/edit/{quizRaw}', 'ExcelController@showEditForm');
Route::post('/excel/edit', 'ExcelController@storeSheet');