<?php namespace CVS\ACLs;

use CVS\Company;
use CVS\Document;
use CVS\Interview;
use CVS\User;

class InterviewACL
{
	public function requiredOrganizer(User $user)
	{
		return $user->organizer;
	}

	public function canRegister(User $user)
	{
		return ($user->organizer || $user->profile_type_str === 'candidate' && $user->profile->canRegisterToInterviews());
	}

	public function canCancel(User $user, Interview $interview)
	{
		return ($user->organizer || $user->profile->id == $interview->candidate_id);
	}
}