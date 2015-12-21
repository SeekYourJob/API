<?php namespace CVS\Mailer;

use CVS\Recruiter;
use CVS\User;

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
			if ($recruiter->parking_option)
				$this->sendToUser($recruiter->user,
					'Votre venue au Job Forum de la FGES',
					'emails.recruiters-map-code',
					[], [], true
				);

		return true;
	}

    public function sendCandidateResumesToRecruiters(Recruiter $recruiter = null)
    {
        $recruiters = [];
        \Log::alert('Sending resumes');
        if (!is_null($recruiter))
            $recruiters[] = $recruiter;
        else
            $recruiters = Recruiter::whereId('3')->get();
//            $recruiters = Recruiter::all()->with('interviews');
        \Log::alert('fetched recruiters :'.sizeof($recruiters));
        foreach ($recruiters as $recruiter)
        {
            \Log::alert('looping');
            if (count($recruiter->interviews))
            {
                \Log::alert('Valid recruiter');
                $data = [
                    'interviews' => Recruiter::getInterviewsForRecruiter($recruiter)
                ];

                $this->sendToUser($recruiter->user,
                    'Votre planning pour le Job Forum de la FGES',
                    'emails.recruiters-planning',
                    $data, [], true
                );
            }
        }
        \Log::alert('Sent Emails');
        return true;
    }
}