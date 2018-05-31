<?php

/*
|--------------------------------------------------------------------------
| Routes File
|--------------------------------------------------------------------------
|
| Here is where you will register all of the routes in an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});


/*
|--------------------------------------------------------------------------
| Logs Routes
|--------------------------------------------------------------------------
|
| This route group applies the «Logs» group to every route
| it contains.
|
*/

Route::get('PerformanceController/sendPerformanceFromDB/{filter}', 'PerformanceController@sendPerformanceFromDB');
Route::get('PerformanceController/getAllServers', 'PerformanceController@getAllServers');
Route::get('PerformanceController/getAllComponents', 'PerformanceController@getAllComponents');
Route::post('PerformanceController/sendPerformanceFromDBByComponentServer', 'PerformanceController@sendPerformanceFromDBByComponentServer');
Route::post('PerformanceController/sendPerformanceFromDBForBarsGraph', 'PerformanceController@sendPerformanceFromDBForBarsGraph');
Route::post('PerformanceController/saveDataToDB', 'PerformanceController@saveDataToDB');
Route::post('TrackingController/saveTrackingDataToDB', 'TrackingController@saveTrackingDataToDB');
Route::post('TrackingController/updateTrackingDataToDB', 'TrackingController@updateTrackingDataToDB');
Route::post('TrackingController/updateTrackingRequestDataToDB', 'TrackingController@updateTrackingRequestDataToDB');
Route::post('TrackingController/saveTrackingRequestDataToDB', 'TrackingController@saveTrackingRequestDataToDB');
Route::post('TrackingController/getLastTrackingKey', 'TrackingController@getLastTrackingKey');
Route::get('TrackingController/getTrackingData', 'TrackingController@getTrackingData');
Route::get('TrackingController/getTrackingRequestsData', 'TrackingController@getTrackingRequestsData');
Route::get('TrackingController/getTrackingDataByTimeFilter', 'TrackingController@getTrackingDataByTimeFilter');
Route::get('TrackingController/getLastLog', 'TrackingController@getLastLog');
Route::post('TrackingController/updateMessageException', 'TrackingController@updateMessageException');


Route::get('log/list', 'LogsController@index');
Route::resource('log', 'LogsController', ['only' => ['show', 'store']]);

Route::get('access/list', 'AccessesController@index');
Route::get('access/action', 'AccessesController@action');
Route::get('access/cb', 'AccessesController@cb');
Route::resource('access', 'AccessesController');

Route::get('analytics/list', 'AnalyticsController@index');

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| This route group applies the "web" middleware group to every route
| it contains. The "web" middleware group is defined in your HTTP
| kernel and includes session state, CSRF protection, and more.
|
*/

Route::group(['middleware' => ['web']], function () {
    //
});
