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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'api', 'middleware' => 'cors'], function (Router $router) {
    $router->get('me', 'AuthenticateController@me');
    $router->post('authenticate', 'AuthenticateController@authenticate');

    $router->get('users', 'UserController@getUsers');
    $router->get('users/{user}', 'UserController@getUser');
    $router->delete('users/{user}', 'UserController@deleteUser');
});
