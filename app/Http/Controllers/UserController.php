<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\User;
use JWTAuth;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

	public function getUsers()
	{
		if (JWTAuth::parseToken()->toUser()->organizer) {
			return User::all();
		}

		abort(401);
//		return response()->json(['error' => 'unauthorized'], 401);
	}

	public function getUser(User $user)
	{
		if (Auth::user()->id === $user->id || Auth::user()->organizer === true) {
			return $user;
		}

		abort(401);
	}

	public function deleteUser(User $user)
	{
		if ( ! $user->organizer && $user->delete()) {
			return response()->json('User deleted.', 200);
		}

		abort(401);
	}
}