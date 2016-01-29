<?php namespace CVS\Mailer;

use CVS\Recruiter;
use CVS\User;
use CVS\Document;
use CVS\Download;

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
        if (!is_null($recruiter))
            $recruiters[] = $recruiter;
        else
            //$recruiters = Recruiter::whereId('3')->get();
            $recruiters = Recruiter::all();
            $attachments = [];
        foreach ($recruiters as $recruiter) {
            if (count($recruiter->interviews)) {
                $data = [
                    'interviews' => Recruiter::getInterviewsForRecruiter($recruiter)
                ];
                $documents = Document::getCandidateDocumentsForRecruiter($recruiter);

                if(count($documents)) {
                    $zipPath = str_replace('\\', '/',storage_path('tmp/') . $recruiter->user->firstname."_".$recruiter->user->lastname . ".zip");

                    if (Download::zipFiles($documents, $zipPath)) {
                            $attachments[] = $zipPath;
                    } else {
                        \Log::alert("failure creating zip attachment for recruiter :".$recruiter->id);
                        return false;
                    }
                }

                $this->sendToUser($recruiter->user,
                    'Votre planning pour le Job Forum de la FGES',
                    'emails.recruiters-planning',
                    $data,$attachments, true
                );
            }
        }
        return true;
    }
}