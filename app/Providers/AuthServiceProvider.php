<?php

namespace CVS\Providers;

use CVS\Company;
use CVS\Policies\CompanyPolicy;
use CVS\User;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
	/**
	 * The policy mappings for the application.
	 *
	 * @var array
	 */
	protected $policies = [

	];

	/**
	 * Register any application authentication / authorization services.
	 *
	 * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
	 * @return void
	 */
	public function boot(GateContract $gate)
	{
		$this->registerPolicies($gate);

		// Company
		$gate->define('show-company', 'CVS\ACLs\CompanyACL@show');
		$gate->define('show-all-companies', 'CVS\ACLs\CompanyACL@showAll');
		$gate->define('update-company', 'CVS\ACLs\CompanyACL@update');

		// User
		$gate->define('show-user', 'CVS\ACLs\UserACL@show');
		$gate->define('show-all-users', 'CVS\ACLs\UserACL@showAll');
		$gate->define('delete-user', 'CVS\ACLs\UserACL@delete');
		$gate->define('get-users-emails', 'CVS\ACLs\UserACL@getEmails');
		$gate->define('get-users-phonenumbers', 'CVS\ACLs\UserACL@getEmails');
		$gate->define('get-users-groups', 'CVS\ACLs\UserACL@getGroups');
		$gate->define('download-document', 'CVS\ACLs\UserACL@downloadDocument');

		// Messaging
		$gate->define('messaging-send-email', 'CVS\ACLs\MessagingACL@sendEmail');
		$gate->define('messaging-send-sms', 'CVS\ACLs\MessagingACL@sendSMS');
		$gate->define('messaging-get-remaining-sms-credits', 'CVS\ACLs\MessagingACL@getSMSCredits');

		// Interviews
		$gate->define('interviews-required-organizer', 'CVS\ACLs\InterviewACL@requiredOrganizer');
		$gate->define('interviews-can-register', 'CVS\ACLs\InterviewACL@canRegister');
		$gate->define('interviews-can-cancel', 'CVS\ACLs\InterviewACL@canCancel');
	}
}