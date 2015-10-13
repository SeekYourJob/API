<?php namespace CVS\Mailer;

use CVS\HistoryEmail;
use Log;
use Mail;

class Mailer
{
	public function sendToEmail($email, $subject, $view, $data = [], $attachments = [], $saveInHistory = true)
	{
		Mail::queue($view, $data, function($message) use($email, $subject, $attachments) {
			$message->from(env('MAIL_FROM'), 'L\'équipe SeekYourJob de la FGES');
//			$message->to($email);
			$message->to(env('MAIL_TEST'), 'Valentin Polo');
			$message->subject($subject);

			foreach($attachments as $attachment)
				$message->attach($attachment);
		});

		// Saving in History...
		HistoryEmail::create([
			'email' => $email,
			'message' => ['subject' => $subject, 'view' => $view]
		]);

		Log::info('[MAIL] Sent to ' . $email . ' with subject ' . $subject . ' and view ' . $view);
	}

	public function sendToUser(\CVS\User $user, $subject, $view, $data = [], $attachments = [])
	{
		if ( ! $user->email_notifications)
			return;

		$allData = array_merge($data, [
			'firstname' => $user->firstname,
			'lastname' => $user->lastname
		]);

		// Sending email...
		$this->sendToEmail($user->email, $subject, $view, $allData, $attachments, false);

		// Saving in History...
		HistoryEmail::create([
			'user_id' => $user->id,
			'message' => ['subject' => $subject, 'view' => $view]
		]);

		Log::info('[MAIL] Sent to ' . $user->firstname . ' ' . $user->lastname . ' with subject ' . $subject . ' and view ' . $view);
	}
}