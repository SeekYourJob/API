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

use Illuminate\Routing\Router;

Route::get('/', function() {
    return response()->json('Welcome to the CVS API!');
});

Route::get('optimus/{id}', function($id) {
    return app('Optimus')->encode($id);
});

Route::get('me', 'AuthenticateController@me');
Route::post('authenticate', 'AuthenticateController@authenticate');

Route::get('users', 'UserController@getUsers');
Route::get('users/{user}', 'UserController@getUser');
Route::delete('users/{user}', 'UserController@deleteUser');
