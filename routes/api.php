<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
Route::group(['prefix' => 'auth'], function () {
    Route::post('/login', 'AuthController@login');
    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/logout', 'AuthController@logout');
        Route::post('/me', 'AuthController@me');
        Route::post('/reset_password', 'AuthController@reset');
    });
});
Route::group(['prefix' => 'poll'], function () {
    Route::group(['middleware' => ['auth:api']], function () {
        Route::post('/', 'PollController@create')->middleware('role:admin');
        Route::get('/', 'PollController@get');
        Route::get('/{poll_id}', 'PollController@details');
        Route::post('/{poll_id}/vote/{choice_id}', 'PollController@vote')->middleware('role:user');
        Route::delete('/{poll_id}', 'PollController@delete')->middleware('role:admin');
    });
});
Route::group(['prefix' => 'dummy'], function () {
    Route::post('/div', 'AuthController@dummydiv');
    Route::post('/', 'AuthController@dummyuser');
});
