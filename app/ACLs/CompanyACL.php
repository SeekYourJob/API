<?php namespace CVS\ACLs;

use CVS\Company;
use CVS\User;

class CompanyACL
{
	public function show(User $user, Company $company)
	{
		return ( $user->organizer || $user->belongsToCompany($company) );
	}

	public function showAll(User $user)
	{
		return $user->organizer;
	}

	public function update(User $user)
	{
		return $user->organizer;
	}
}