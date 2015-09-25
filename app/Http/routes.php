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
    $test = \CVS\Slot::hydrateRaw('
        SELECT s.*, COUNT(s.id) AS total_slots, CAST(SUM(IF(i.candidate_id IS NULL, 1, 0)) AS UNSIGNED INTEGER) AS free_slots, GROUP_CONCAT(i.candidate_id) AS candidates
        FROM slots s
        LEFT OUTER JOIN interviews i ON s.id = i.slot_id AND i.company_id = 7
        GROUP BY s.id');
//    dd($test);
    return $test;
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
Route::get('logout', 'AuthenticateController@logout');

Route::get('users', 'UsersController@getUsers');
Route::get('users/{user}', 'UsersController@getUser');
Route::delete('users/{user}', 'UsersController@deleteUser');

Route::get('companies/{companies}/recruiters', 'CompaniesController@showRecruiters');
Route::resource('companies', 'CompaniesController');

Route::resource('recruiters', 'RecruitersController');

Route::post('documents', 'DocumentsController@create');
