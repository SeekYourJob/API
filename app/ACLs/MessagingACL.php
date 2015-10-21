<?php namespace CVS\ACLs;

use CVS\Company;
use CVS\User;

class MessagingACL
{
	public function sendEmail(User $user)
	{
		return $user->organizer;
	}

	public function sendSMS(User $user)
	{
		return $user->organizer;
	}

	public function getSMSCredits(User $user)
	{
		return $user->organizer;
	}
}