<?php namespace CVS\Mailer;

use Log;
use Mail;

class Mailer
{
	public function sendToUser(\CVS\User $user, $subject, $view, $data = [], $attachments = [])
	{
		$allData = array_merge($data, [
			'firstname' => $user->firstname,
			'lastname' => $user->lastname
		]);

		Mail::queue($view, $allData, function($message) use($user, $subject, $attachments) {
			$message->from('cvs@fges.info', 'L\'équipe CVS de la FGES');
//			$message->to($user->email, $user->firstname . ' ' . $user->lastname);
			$message->to(env('MAIL_TEST'), 'Valentin Polo');
			$message->subject($subject);

			foreach($attachments as $attachment)
				$message->attach($attachment);
		});

		Log::info('[REAL] Sending EMAIL to ' . $user->email . ' of subject ' . $subject);
	}
}