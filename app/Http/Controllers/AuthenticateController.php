<?php

namespace CVS\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['only' => ['test']]);
	}

	public function authenticate(Request $request)
	{
		$credentials = $request->only('email', 'password');

		try {
			if ( ! $token = JWTAuth::attempt($credentials)) {
				return response()->json('Invalid credentials.', 401);
			}
		} catch (JWTException $e) {
			return response()->json('Could not create token', 500);
		}

		return response()->json(compact('token'));
	}

	public function test()
	{
		return response()->json('You should only see this if theres a JWTtoken!');
	}
}