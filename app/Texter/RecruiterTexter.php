<?php  namespace CVS\Texter;

use CVS\Recruiter;

class RecruiterTexter extends Texter
{
	public function sendParkingCodeToRecruiters(Recruiter $recruiter = null)
	{
		$recruiters = [];

		if (!is_null($recruiter))
			$recruiters[] = $recruiter;
		else
			$recruiters = Recruiter::all();

		foreach ($recruiters as $recruiter)
			if ($recruiter->parking_option)
				$this->sendToUser($recruiter->user,
					"Bonjour " . $recruiter->user->firstname . ",\r\n\r\nPour rappel, voici le code d'accès au parking de la FGES : XXXX (https://goo.gl/maps/tqjwrVabGn12)",
					true
				);

		return true;
	}
}