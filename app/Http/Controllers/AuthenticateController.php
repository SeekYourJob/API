<?php

namespace CVS\Http\Controllers;

use CVS\Company;
use CVS\Document;
use CVS\Http\Requests\RegisterRecruiterRequest;
use CVS\Jobs\RegisterRecruiter;
use CVS\Jobs\SendTextToPhoneNumber;
use CVS\Mailer\Mailer;
use CVS\Recruiter;
use CVS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use libphonenumber\PhoneNumberUtil;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['only' => ['me', 'checkOrganizer']]);
	}

	public function test2()
	{
		$user = User::find(1)->with(['sentTexts', 'sentEmails'])->get();

		return $user;

//		$this->dispatch(new SendTextToPhoneNumber("+33123456789", "This is going to be awesome!"));
	}

	public function checkEmail(Request $request)
	{
		if (User::where('email', $request->get('email'))->count()) {
			return response()->json(['error' => 'email_already_taken'], 409);
		}

		return response('');
	}

	public function checkOrganizer()
	{
		if ($user = JWTAuth::parseToken()->authenticate())
			if ($user->organizer)
				return response('');

		abort(401);
	}

	public function registerRecruiter(RegisterRecruiterRequest $request)
	{
		if ($user = $this->dispatchFrom(RegisterRecruiter::class, $request)) {
			return response()->json($user);
		}

		abort(500, "Recruiter registration failed.");
	}

	public function registerCandidate()
	{

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

	public function refresh(Request $request)
	{
		return response()->json(['token' => JWTAuth::refresh($request->get('oldToken'))]);
	}

	public function logout(Request $request)
	{
		if (JWTAuth::parseToken()->invalidate())
			return response('');

		abort(500, "Logout failed!");
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