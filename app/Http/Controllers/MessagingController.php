<?php

namespace CVS\Http\Controllers;

use CVS\Http\Requests\MessagingSendEmailRequest;
use CVS\Http\Requests;

class MessagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

    public function sendEmail(MessagingSendEmailRequest $request)
    {
	    \Log::info($request->all());

	    return response()->json('we should send an email!');
    }
}
