<?php

namespace CVS\Texter;

use CVS\Candidate;
use CVS\Interview;

class CandidateTexter extends Texter
{
	public function sendInterviewReminderToCandidate(Interview $interview)
	{
		$this->sendToUser($interview->candidate->user,
			"Bonjour " . $interview->candidate->user->firstname . ",\r\nVotre entretien est confirmé avec " . $interview->recruiter->company->name . " aujourd'hui à " . $interview->slot->begins_at_formatted . " !",
			true
		);
	}

	public function sendNoticeInterviewHasBeenCancelledToCandidate(Interview $interview, Candidate $previousCandidate)
	{
		if (isset($previousCandidate->user, $interview->slot, $interview->recruiter))
			$this->sendToUser($previousCandidate->user,
				"Bonjour " . $previousCandidate->user->firstname . ",\r\nVotre entretien de "  . $interview->slot->begins_at_formatted . " avec " . $interview->recruiter->company->name . " a été annulé.",
				true
			);
	}
}