<?php

namespace CVS\Http\Controllers;

use Illuminate\Http\Request;
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
		} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
			return response()->json('Could not create token', 500);
		}

		return response()->json(compact('token'));
	}

	public function me()
	{
		try {
			if ( ! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json('User not found', 404);
			}
		} catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return response()->json('Token expired.', $e->getStatusCode());
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return response()->json('Token invalid.', $e->getStatusCode());
		} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
			return response()->json('Token missing.', $e->getStatusCode());
		}

		return response()->json(compact('user'));
	}
}