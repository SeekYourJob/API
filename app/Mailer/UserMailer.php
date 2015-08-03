<?php namespace CVS\Mailer;

use CVS\Recruiter;

class UserMailer extends Mailer
{
	public function welcomeRecruiter(Recruiter $recruiter)
	{
		$this->sendToUser($recruiter->user,
			'Inscription au Jobs Dating de la filière info de la FGES',
			'emails.register-recruiter',
			[],
			[
				public_path('assets/files/event.ics')
			]
		);
	}
}