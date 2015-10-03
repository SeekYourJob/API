<?php

use CVS\Company;
use CVS\Enums\InterviewStatus;
use CVS\Slot;

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

Route::get('test2', function() {

    echo json_encode(\CVS\Interview::getAllForAllCompanies());

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

Route::post('recruiters/generate-interviews', 'RecruitersController@generateInterviews');
Route::resource('recruiters', 'RecruitersController');

Route::post('documents', 'DocumentsController@create');
