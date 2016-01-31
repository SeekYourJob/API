<?php

namespace CVS\Listeners;

use CVS\Events\InterviewWasRegistered;
use CVS\Mailer\CandidateMailer;
use CVS\Slot;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCandidateInterviewWasRegistered implements shouldQueue
{
    public $candidateMailer;

    public function __construct(CandidateMailer $candidateMailer)
    {
        $this->candidateMailer = $candidateMailer;
    }

    public function handle(InterviewWasRegistered $event)
    {
        if (Slot::isBigDay()) {
            $this->candidateMailer->sendInterviewReminderToCandidate($event->interview);
        }
    }
}
