<?php

use CVS\Company;
use CVS\Slot;

Route::get('/', function() {
    return response()->json('Welcome to the SeekYourJob API!');
});

Route::get('test', function() {

    return Company::getInterviewsGroupedByCompanies();

});

Route::get('test2', 'AuthenticateController@test2');

Route::get('me', 'AuthenticateController@me');
Route::get('logout', 'AuthenticateController@logout');
Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/refresh', 'AuthenticateController@refresh');
Route::get('authenticate/check-email', 'AuthenticateController@checkEmail');
Route::get('authenticate/check-organizer', 'AuthenticateController@checkOrganizer');
Route::post('authenticate/register-recruiter', 'AuthenticateController@registerRecruiter');


Route::get('users', 'UsersController@getUsers');
Route::get('users/groups', 'UsersController@getGroups');
Route::get('users/emails', 'UsersController@getEmails');
Route::get('users/phone-numbers', 'UsersController@getPhoneNumbers');
Route::get('users/{user}', 'UsersController@getUser');
Route::delete('users/{user}', 'UsersController@deleteUser');

Route::get('companies/{companies}/recruiters', 'CompaniesController@showRecruiters'); // TOK
Route::resource('companies', 'CompaniesController');

Route::resource('recruiters', 'RecruitersController');

Route::get('candidates/{candidates}/interviews', 'CandidatesController@getInterviewsForCandidate');

Route::get('interviews', 'InterviewsController@getAll');
Route::post('interviews', 'InterviewsController@createInterview');
Route::get('interviews/slots', 'InterviewsController@getAllSlots');
Route::post('interviews/generate', 'InterviewsController@generate');
Route::post('interviews/register', 'InterviewsController@register');
Route::delete('interviews/{interviews}', 'InterviewsController@deleteInterview');
Route::post('interviews/{interviews}/free', 'InterviewsController@freeInterview');

Route::get('interviews/company/{companies}', 'InterviewsController@getAllForCompany');
Route::get('interviews/recruiter/{recruiters}', 'InterviewsController@getAllForRecruiter');


Route::post('messaging/send-email', 'MessagingController@sendEmail');
Route::post('messaging/send-sms', 'MessagingController@sendSMS');
Route::get('messaging/remaining-sms-credits', 'MessagingController@getRemainingSMSCredits');

Route::post('documents', 'DocumentsController@create');
Route::get('documents/user/{user}', 'DocumentsController@getFilesForUser');
Route::get('documents/request-token/{documents}', 'DocumentsController@getRequestTokenForDocument');
Route::get('documents/{requestToken}', 'DocumentsController@getFile');
Route::delete('documents/{documents}', 'DocumentsController@deleteFile');
