<?php

namespace CVS\Http\Controllers;

use CVS\Http\Requests\MessagingSendEmailRequest;
use CVS\Http\Requests;
use CVS\Http\Requests\MessagingSendSMSRequest;
use CVS\Mailer\Mailer;
use CVS\Texter\Texter;
use CVS\User;

class MessagingController extends Controller
{
	public function __construct()
	{
		$this->middleware('jwt.auth');
	}

	public function getRemainingSMSCredits()
	{
		$this->authorize('messaging-get-remaining-sms-credits');

		return response()->json(Texter::getRemainingCredits());
	}

    public function sendEmail(MessagingSendEmailRequest $request)
    {
	    $this->authorize('messaging-send-email');

	    $mailer = new Mailer();
	    foreach($request->input('recipients') as $recipient)
		   $mailer->sendToUser(User::whereId(app('Hashids')->decode($recipient)[0])->firstOrFail(),
			    $request->input('message.object'),
			    'emails.skeleton',
			    ['content' => nl2br($request->input('message.content'))],
			   [],
			   true
		    );

	    return response()->json('Mail sending queued');
    }

	public function sendSMS(MessagingSendSMSRequest $request)
	{
		$this->authorize('messaging-send-sms');

		$texter = new Texter();
		foreach($request->input('recipients') as $recipient)
			$texter->sendToUser(User::whereId(app('Hashids')->decode($recipient)[0])->firstOrFail(), $request->input('message'));

		return response()->json('Text message sending queued');
	}
}
