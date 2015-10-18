<?php namespace CVS\ACLs;

use CVS\Company;
use CVS\User;

class MessagingACL
{
	public function sendEmails(User $user)
	{
		return $user->organizer;
	}
}