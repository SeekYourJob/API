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

Route::get('/', function() {
    return response()->json('Welcome to the CVS API!');
});

Route::get('test', function() {
   return view('emails.register-recruiter');
});

Route::get('optimus/{id}', function($id) {
    return app('Optimus')->encode($id);
});

Route::get('me', 'AuthenticateController@me');

Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/refresh', 'AuthenticateController@refresh');
Route::get('authenticate/check-email', 'AuthenticateController@checkEmail');
Route::get('authenticate/check-organizer', 'AuthenticateController@checkOrganizer');
Route::post('authenticate/register-recruiter', 'AuthenticateController@registerRecruiter');

Route::get('users', 'UserController@getUsers');
Route::get('users/{user}', 'UserController@getUser');
Route::delete('users/{user}', 'UserController@deleteUser');

Route::post('documents', 'DocumentsController@create');
