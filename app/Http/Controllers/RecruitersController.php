<?php

namespace CVS\Http\Controllers;

use Auth;
use CVS\Recruiter;
use Illuminate\Http\Request;

use CVS\Http\Requests;

class RecruitersController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth');
        $this->middleware('organizer', ['except' => ['show']]);
    }

    public function index()
    {
        return Recruiter::with(['company', 'user'])->get();
    }

    public function show(Recruiter $recruiter)
    {
        if (Auth::user()->organizer || Auth::user()->id === $recruiter->user->id) {
            return $recruiter::with(['user', 'company'])
                ->whereId($recruiter->id)
                ->first();
        }

        abort(401);
    }

    public function update(Request $request, Recruiter $recruiter)
    {
        if (Auth::user()->organizer) {
            try {
                $recruiter->user->update([
                    'email' => $request->input('user.email'),
                    'firstname' => $request->input('user.firstname'),
                    'lastname' => $request->input('user.lastname'),
                ]);
                return $recruiter;
            } catch (Exception $e) {
                abort(500);
            }
        }

        abort(401);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
