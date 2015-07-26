<?php
/**
 * Created by PhpStorm.
 * User: Valentin
 * Date: 26/07/2015
 * Time: 17:35
 */

namespace CVS\Http\Controllers;


use Auth;
use CVS\User;

class UserController extends Controller
{
	public function __construct()
	{
		$this->middleware('auth');
		$this->middleware('organizer', ['only' => ['getUsers']]);
	}

	public function getUsers()
	{
		return User::all();
	}

	public function getUser(User $user)
	{
		if (Auth::user()->id === $user->id || Auth::user()->organizer === true) {
			return $user;
		}

		abort(401);
	}
}