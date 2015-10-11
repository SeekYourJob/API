<?php namespace CVS\Mailer;

use Log;
use Mail;

class Mailer
{
	public function sendToEmail($email, $subject, $view, $data = [], $attachments = [])
	{
		Mail::queue($view, $data, function($message) use($email, $subject, $attachments) {
			$message->from(env('MAIL_FROM'), 'L\'équipe SeekYourJob de la FGES');
//			$message->to($email);
			$message->to(env('MAIL_TEST'), 'Valentin Polo');
			$message->subject($subject);

			foreach($attachments as $attachment)
				$message->attach($attachment);

			Log::info('Mail SENT to ' . $email);
		});
	}

	public function sendToUser(\CVS\User $user, $subject, $view, $data = [], $attachments = [])
	{
		$allData = array_merge($data, [
			'firstname' => $user->firstname,
			'lastname' => $user->lastname
		]);

		Mail::queue($view, $allData, function($message) use($user, $subject, $attachments) {
			$message->from(env('MAIL_FROM'), 'L\'équipe SeekYourJob de la FGES');
//			$message->to($user->email, $user->firstname . ' ' . $user->lastname);
			$message->to(env('MAIL_TEST'), 'Valentin Polo');
			$message->subject($subject);

			foreach($attachments as $attachment)
				$message->attach($attachment);
		});

		Log::info('Sending EMAIL to ' . $user->email . ' of subject ' . $subject);
	}
}