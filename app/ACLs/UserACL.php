<?php namespace CVS\ACLs;

use CVS\Company;
use CVS\User;

class UserACL
{
	public function show(User $user, User $userToShow)
	{
		return ( $user->organizer || $user->id === $userToShow->id );
	}

	public function showAll(User $user)
	{
		return $user->organizer;
	}

	public function update(User $user)
	{
		return $user->organizer;
	}

	public function delete(User $user, User $userToDelete)
	{
		return $user->organizer && ! $userToDelete->organizer;
	}

	public function getEmails(User $user)
	{
		return $user->organizer;
	}

	public function getGroups(User $user)
	{
		return $user->organizer;
	}
}