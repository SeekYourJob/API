<?php

namespace CVS\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['except' => ['authenticate', 'refresh']]);
	}

	public function authenticate(Request $request)
	{
		Log::info('/authenticate: Token requested for ' . $request->get('email'));

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

	public function refresh(Request $request)
	{
		Log::info('/authenticate/refresh: Token refresh requested');

		return response()->json(['token' => JWTAuth::refresh($request->get('oldToken'))]);
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

		return response()->json(['user' => [
			'ido' => app('Optimus')->encode($user->id),
			'profile' => $user->profile_type,
			'firstname' => $user->firstname,
			'lastname' => $user->lastname,
			'email' => $user->email,
			'phone' => $user->phone,
			'organizer' => $user->organizer,
			'notifications' => [
				'email' => $user->email_notifications,
				'sms' => $user->sms_notifications
			]
		]]);
	}
}