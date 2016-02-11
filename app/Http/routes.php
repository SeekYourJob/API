<?php

use CVS\Company;
use CVS\Download;
use CVS\Interview;
use CVS\Location;
use CVS\Slot;

Route::get('/', function() {
    return response()->json('Welcome to the SeekYourJob API!');
});

Route::get('/test', function(Request $request) {
    $pdf = app('dompdf.wrapper');
    $pdf->loadView('pdfs.recruiters-planning');
    return $pdf->stream();
});

Route::get('me', 'AuthenticateController@me');
Route::get('logout', 'AuthenticateController@logout');
Route::post('authenticate', 'AuthenticateController@authenticate');
Route::post('authenticate/refresh', 'AuthenticateController@refresh');
Route::get('authenticate/check-email', 'AuthenticateController@checkEmail');
Route::post('authenticate/pusher-token', 'AuthenticateController@pusherToken');
Route::get('authenticate/check-organizer', 'AuthenticateController@checkOrganizer');
Route::post('authenticate/do-reset-password', 'AuthenticateController@doResetPassword');
Route::post('authenticate/ask-reset-password', 'AuthenticateController@askResetPassword');
Route::post('authenticate/register-recruiter', 'AuthenticateController@registerRecruiter');
Route::post('authenticate/register-candidate', 'AuthenticateController@registerCandidate');

Route::get('users', 'UsersController@getUsers');
Route::get('users/groups', 'UsersController@getGroups');
Route::get('users/emails', 'UsersController@getEmails');
Route::get('users/phone-numbers', 'UsersController@getPhoneNumbers');
Route::get('users/{user}', 'UsersController@getUser');
Route::delete('users/{user}', 'UsersController@deleteUser');

Route::get('companies/{companies}/recruiters', 'CompaniesController@showRecruiters'); // TOK
Route::get('companies/{companies}/offers', 'CompaniesController@showOffers');
Route::resource('companies', 'CompaniesController');

Route::resource('recruiters', 'RecruitersController');

Route::get('candidates/{candidates}/summary', 'CandidatesController@showSummary');
Route::resource('candidates', 'CandidatesController');

Route::get('interviews', 'InterviewsController@getAll');
Route::post('interviews/cancel', 'InterviewsController@cancel');
Route::post('interviews', 'InterviewsController@createInterview');
Route::get('interviews/slots', 'InterviewsController@getAllSlots');
Route::post('interviews/generate', 'InterviewsController@generate');
Route::post('interviews/register', 'InterviewsController@register');
Route::delete('interviews/{interviews}', 'InterviewsController@deleteInterview');
Route::post('interviews/{interviews}/free', 'InterviewsController@freeInterview');
Route::post('interviews/{interviews}/toggle-status', 'InterviewsController@toggleStatusInterview');
Route::get('interviews/company/{companies}', 'InterviewsController@getAllForCompany');
Route::get('interviews/candidate/{candidates}', 'InterviewsController@getAllForCandidate');
Route::get('interviews/candidate-by-company/{candidates}', 'InterviewsController@getAllForCandidateByCompany');
Route::get('interviews/recruiter/{recruiters}', 'InterviewsController@getAllForRecruiter');
Route::get('interviews/candidates-available-for-slot-and-company', 'InterviewsController@getAvailableStudentsForGivenSlotAndCompany');

Route::post('messaging/send-sms', 'MessagingController@sendSMS');
Route::post('messaging/send-email', 'MessagingController@sendEmail');
Route::get('messaging/predefined-sms', 'MessagingController@getPredefinedSMS');
Route::get('messaging/predefined-emails', 'MessagingController@getPredefinedEmails');
Route::post('messaging/send-predefined-sms', 'MessagingController@sendPredefinedSMS');
Route::post('messaging/send-predefined-email', 'MessagingController@sendPredefinedEmail');
Route::get('messaging/remaining-sms-credits', 'MessagingController@getRemainingSMSCredits');

Route::post('documents', 'DocumentsController@create');
Route::get('documents/candidates', 'DocumentsController@getAllFilesForCandidates');
Route::get('documents/user/{user}', 'DocumentsController@getFilesForUser');
Route::get('documents/request-token/{documents}', 'DocumentsController@getRequestTokenForDocument');
Route::get('documents/{requestToken}', 'DocumentsController@getFile');
Route::delete('documents/{documents}', 'DocumentsController@deleteFile');
Route::post('documents/{documents}/accept', 'DocumentsController@acceptDocument');
Route::post('documents/{documents}/refuse', 'DocumentsController@refuseDocument');

Route::get('locations', 'LocationsController@getAll');
Route::get('locations/bookings', 'LocationsController@getBookings');
Route::get('locations/interviews-for-slot/{slots}', 'LocationsController@getAllWithInterviewsForSlot');
Route::get('locations/interviews-for-current-slot/', 'LocationsController@getAllWithInterviewsForCurrentSlot');
Route::get('locations/missing', 'LocationsController@getMissingLocationsForInterviews');
Route::put('locations/update-interview/{interviews}', 'LocationsController@updateInterview');
Route::put('locations/update-recruiter/{recruiters}', 'LocationsController@updateRecruiter');

Route::get('stats/interviews', 'StatsController@getInterviewsStats');