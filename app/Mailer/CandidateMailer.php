<?php namespace CVS\Mailer;

use CVS\Candidate;
use CVS\Interview;
use CVS\User;
use CVS\Document;

class CandidateMailer extends Mailer
{
    public function refuseCandidateDocument(Document $document)
    {
        $user = $document->user;

        $data = [
            'documentName' => $document->name,
        ];

        $this->sendToUser($user,
            'Votre CV a été refusé !',
            'emails.refuse-document',
            $data,[], true
        );

        return true;
    }

    public function acceptCandidateDocument(Document $document)
    {
        $user = $document->user;

        $data = [
            'documentName' => $document->name,
        ];

        $this->sendToUser($user,
            'Votre CV a été accepté !',
            'emails.accept-document',
            $data, [], true
        );

        return true;
    }

    public function sendInterviewReminderToCandidate(Interview $interview)
    {
        $this->sendToUser($interview->candidate->user,
            "Nouvel entretien avec " . $interview->recruiter->company->name,
            "emails.interview-bigday-reminder",
            [
                'interviewCompanyName' => $interview->recruiter->company->name,
                'interviewBeginsAt' => $interview->slot->begins_at_formatted
            ], [], true
        );
    }

    public function sendNoticeInterviewHasBeenCancelledToCandidate(Interview $interview, Candidate $previousCandidate)
    {
        $this->sendToUser($previousCandidate->user,
            "Annulation de votre entretien avec " . $interview->recruiter->company->name,
            "emails.interview-bigday-cancellation",
            [
                'interviewCompanyName' => $interview->recruiter->company->name,
                'interviewBeginsAt' => $interview->slot->begins_at_formatted
            ], [], true
        );
    }

    public function sendPlanningToCandidates()
    {
            $candidates = Candidate::all();

            foreach ($candidates as $candidate) {
                if (count($candidate->interviews)) {
                    $data = [
                        'interviews' => Candidate::getInterviewsForCandidate($candidate)
                    ];

                    $this->sendToUser($candidate->user,
                        'Votre planning pour le Job Forum de la FGES',
                        'emails.candidates-planning',
                        $data,[], true
                    );
                }
            }

            return true;
    }


    public function sendNoInterviewsWarningToCandidates()
    {
        $candidates = Candidate::all();

        foreach ($candidates as $candidate) {
            if (!count($candidate->interviews)) {
                $this->sendToUser($candidate->user,
                    'Vous n\'avez pas choisi d\'entretiens',
                    'emails.candidates-planning-no-interviews',
                    [], [], true
                );
            }
        }

        return true;
    }
}