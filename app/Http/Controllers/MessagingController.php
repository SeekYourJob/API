<?php

namespace CVS\Http\Controllers;

use CVS\Http\Requests\MessagingSendEmailRequest;
use CVS\Http\Requests;
use CVS\Mailer\Mailer;
use CVS\User;

class MessagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

    public function sendEmail(MessagingSendEmailRequest $request)
    {
	    $this->authorize('messaging-send-emails');

	    $mailer = new Mailer();
	    foreach($request->input('recipients') as $recipient) {
		   $mailer->sendToUser(User::whereId(app('Optimus')->decode($recipient))->firstOrFail(),
			    $request->input('message.object'),
			    'emails.skeleton',
			    ['content' => nl2br($request->input('message.content'))],
			   [],
			   true
		    );
	    }

	    return response()->json('Mail sent');
    }
}
