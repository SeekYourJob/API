<?php

namespace CVS\Http\Controllers;

use CVS\User;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

	/**
	 * Returns all Users
	 * @return \Illuminate\Database\Eloquent\Collection|static[]
	 */
	public function getUsers()
	{
		$this->authorize('show-all-users');

		return User::all();
	}

	/**
	 * @param User $user
	 * Return the specified User
	 * @return User
	 */
	public function getUser(User $user)
	{
		$this->authorize('show-user', $user);

		return $user;
	}

	/**
	 * @param User $user
	 * Delete the specified User
	 * @return \Illuminate\Http\JsonResponse
	 * @throws \Exception
	 */
	public function deleteUser(User $user)
	{
		$this->authorize('delete-user', $user);

		return $user->delete() ? response()->json('User deleted.', 200) : response()->json('User NOT deleted.', 500);
	}
}