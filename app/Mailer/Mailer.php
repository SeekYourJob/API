<?php namespace CVS\Mailer;

use CVS\HistoryEmail;
use Log;
use Mail;

class Mailer
{
	public function sendToEmail($email, $identity, $subject, $view, $data = [], $attachments = [], $saveInHistory = true)
	{
		Mail::queue($view, $data, function($message) use($email, $identity, $subject, $attachments) {
			$message->from(env('MAIL_FROM_EMAIL'), env('MAIL_FROM_NAME'));
			$message->to($email, $identity);
			$message->subject($subject);

			foreach($attachments as $attachment)
				$message->attach($attachment);
		});

		// Saving in History...
		if ($saveInHistory)
			HistoryEmail::create([
				'email' => $email,
				'message' => [
					'subject' => $subject,
					'message' => (isset($data['content']) && !empty($data['content'])) ? $data['content'] : null,
					'data' => $data,
					'view' => $view
				]
			]);
	}

	public function sendToUser(\CVS\User $user, $subject, $view, $data = [], $attachments = [], $forceSend = false)
	{
		if (!$forceSend && !$user->email_notifications)
			return;

		$allData = array_merge($data, [
			'firstname' => $user->firstname,
			'lastname' => $user->lastname
		]);

        \Log::alert('sending to user :'.$user);

		// Sending email...
		$this->sendToEmail($user->email, $user->firstname . ' ' . $user->lastname, $subject, $view, $allData, $attachments, false);

		// Saving in History...
		HistoryEmail::create([
			'user_id' => $user->id,
			'message' => [
				'subject' => $subject,
				'message' => (isset($data['content']) && !empty($data['content'])) ? $data['content'] : null,
				'data' => $data,
				'view' => $view
			]
		]);
	}
}