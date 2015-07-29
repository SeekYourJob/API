<?php

namespace CVS\Http\Controllers;

use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['authenticate']]);
	}

	public function authenticate(Request $request)
	{
		$credentials = $request->only('email', 'password');

		try {
			if ( ! $token = JWTAuth::attempt($credentials)) {
				return response()->json(['error' => 'invalid_credentials'], 401);
			}
		} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
			return response()->json(['error' => 'could_not_create_token'], 500);
		}

		return response()->json(compact('token'));
	}

	public function me()
	{
		try {
			if ( ! $user = JWTAuth::parseToken()->authenticate()) {
				return response()->json(['error' => 'user_not_found'], 404);
			}
		} catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
			return response()->json(['error' => 'expired_token'], $e->getStatusCode());
		} catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
			return response()->json(['error' => 'invalid_token'], $e->getStatusCode());
		} catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
			return response()->json(['error' => 'missing_token'], $e->getStatusCode());
		}

		return response()->json(compact('user'));
	}
}