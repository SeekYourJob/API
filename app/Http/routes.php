<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api'], function () {
    Route::post('authenticate', 'AuthenticateController@authenticate');
    Route::get('authenticate/test', 'AuthenticateController@test');

    Route::get('users', 'UserController@getUsers');
    Route::get('users/{user}', 'UserController@getUser');
    Route::delete('users/{user}', 'UserController@deleteUser');
});
