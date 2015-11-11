<?php namespace CVS\Mailer;

use CVS\Recruiter;
use CVS\User;

class UserMailer extends Mailer
{
	public function sendResetPassword(User $user)
	{
		$this->sendToUser($user,
				'Réinitialiser votre mot de passe SeekYourJob',
				'emails.reset-password',
				['reset_password_token' => $user->reset_password_token], [], true
		);
	}

	public function welcomeRecruiter(Recruiter $recruiter)
	{
		$this->sendToUser($recruiter->user,
			'Votre inscription au Jobs Dating de la filière informatique de la FGES',
			'emails.register-recruiter',
			[],
			[
				public_path('assets/files/event.ics')
			]
		);
	}

	public function welcomeInvitedRecruiter(Recruiter $referral, Recruiter $recruiter, $generatedPassword)
	{
		$data = [
			'referralFirstname' => $referral->user->firstname,
			'referralLastname' => $referral->user->lastname,
			'recruiterEmail' => $recruiter->user->email,
			'generatedPassword' => $generatedPassword
		];

		$this->sendToUser($recruiter->user,
			'Votre inscription au Job Forum SeekYourJob de la FGES',
			'emails.register-invited-recruiter',
			$data);
	}

	public function inviteParticipant(Recruiter $referral, $email)
	{
		$data = [
			'referralFirstname' => $referral->user->firstname,
			'referralLastname' => $referral->user->lastname,
			'referralCompany' => $referral->company->name,
			'referralAvailability' => $referral->availability
		];

		$this->sendToEmail($email,
			$referral->user->firstname . ' ' . $referral->user->lastname . ' vous invite au Job Forum SeekYourJob de la FGES',
			'emails.invite-recruiter',
			$data);
	}
}