<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Company;
use CVS\Document;
use CVS\Http\Requests\RegisterRecruiterRequest;
use CVS\Interview;
use CVS\Jobs\RegisterRecruiter;
use CVS\Recruiter;
use CVS\Texter\Texter;
use CVS\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Tymon\JWTAuth\Facades\JWTAuth;
use Vinkla\Hashids\Facades\Hashids;

class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['only' => ['me', 'checkOrganizer']]);
	}

	public function test2()
	{
		dd(app('Hashids')->encode(1));
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

    public function me(Request $request)
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['error' => 'user_not_found'], 404);
            }
        } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['error' => 'expired_token'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['error' => 'invalid_token'], $e->getStatusCode());
        } catch (\Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['error' => 'missing_token'], $e->getStatusCode());
        }

        $profile = ['user' => [
            'ido' => $user->ido,
            'profile' => $user->profile_type,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'email' => $user->email,
	        'email_md5' => md5($user->email),
            'phone' => $user->phone,
            'organizer' => $user->organizer,
            'notifications' => [
                'email' => $user->email_notifications,
                'sms' => $user->sms_notifications
            ]
        ]];

        if ($request->has('showDetails')) {
            if ($user->profile_type === 'CVS\\Recruiter') {
                $recruiter = Recruiter::whereId($user->profile_id)->first();
                $interviews = Interview::getAllForRecruiter($recruiter);
                $profile['user']['recruiter'] = $interviews;

            } elseif ($user->profile_type === 'CVS\\Candidate') {
//                $profile['user']['candidate'] = [
//		            'candidate' => $user->profile
//	            ];
            }
        }

        return response()->json($profile);
    }
}