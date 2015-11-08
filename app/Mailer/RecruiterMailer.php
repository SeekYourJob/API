<?php namespace CVS\Mailer;

use CVS\Recruiter;

class RecruiterMailer extends Mailer
{
	public function sendMapAndParkingCodeToRecruiters(Recruiter $recruiter = null)
	{
		$recruiters = [];

		if (!is_null($recruiter))
			$recruiters[] = $recruiter;
		else
			$recruiters = Recruiter::all();

		foreach ($recruiters as $recruiter)
			$this->sendToUser($recruiter->user,
				'Votre venue au Job Forum de la FGES',
				'emails.recruiters-map-code',
				[], [], true
			);

		return true;
	}
}