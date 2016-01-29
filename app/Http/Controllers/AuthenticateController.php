<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Company;
use CVS\Document;
use CVS\Http\Requests\RegisterRecruiterRequest;
use CVS\Interview;
use CVS\Jobs\RegisterRecruiter;
use CVS\Mailer\RecruiterMailer;
use CVS\Mailer\UserMailer;
use CVS\Recruiter;
use CVS\Slot;
use CVS\Texter\Texter;
use CVS\User;
use Exception;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Password;
use Tymon\JWTAuth\Facades\JWTAuth;
use Vinkla\Hashids\Facades\Hashids;

class AuthenticateController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth', ['only' => ['test', 'me', 'checkOrganizer', 'pusherToken']]);
	}

	public function test()
	{
		return Interview::getByLocationsForCurrentAndNextSlot(Slot::find(2));
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

	public function pusherToken(Request $request)
	{
		return response(app('Pusher')->presence_auth($request->get('channel_name'), $request->get('socket_id'), Auth::user()->id, []));
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
	        'profile_ido' => $user->profile->ido,
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
	            $profile['user']['recruiter'] = [
		            'interviews' => Interview::getAllForRecruiter($user->profile)['interviews'],
		            'company' => $user->profile->company
	            ];

            } elseif ($user->profile_type === 'CVS\\Candidate') {
                $profile['user']['candidate'] = [
		            'canRegisterToInterviews' => $user->profile->canRegisterToInterviews()
	            ];
            }
        }

        return response()->json($profile);
    }

	public function doResetPassword(Request $request)
	{
		$this->validate($request, [
			'password' => 'required',
			'confirmation' => 'required|same:password',
			'token' => 'required|exists:users,reset_password_token'
		]);

		try {
			$user = User::whereResetPasswordToken($request->get('token'))->firstOrFail();
			$user->password = Hash::make($request->get('password'));
			$user->reset_password_token = null;
			$user->save();

			return response()->json('Password changed');

		} catch (Exception $e) {
			abort(500, 'Could not change password');
		}



		return $user;
	}

	public function askResetPassword(Request $request, UserMailer $userMailer)
	{
		$this->validate($request, ['email' => 'required|email']);

		if ($user = User::whereEmail($request->get('email'))->firstOrFail()) {
			$user->reset_password_token = str_random(42);
			$user->save();

			$userMailer->sendResetPassword($user);

			return $user;
		}
	}
}