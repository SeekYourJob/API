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

	public function getUsers()
	{
		if (Auth::user()->organizer) {
			return User::all();
		}

		abort(401);
	}

	public function getUser(User $user)
	{
		if (Auth::user()->id == $user->id || Auth::user()->organizer == true) {
			return $user;
		}

		abort(401);
	}

	public function deleteUser(User $user)
	{
		if (Auth::user()->organizer && !$user->organizer && $user->delete()) {
			return response()->json('User deleted.', 200);
		}

		abort(401);
	}
}