<?php

namespace CVS\Listeners;

use CVS\Events\InterviewWasCanceled;
use CVS\Mailer\CandidateMailer;
use CVS\Slot;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCandidateInterviewWasCanceled implements shouldQueue
{
    public $candidateMailer;

    public function __construct(CandidateMailer $candidateMailer)
    {
        $this->candidateMailer = $candidateMailer;
    }

    public function handle(InterviewWasCanceled $event)
    {
//        if (Slot::isBigDay())
            $this->candidateMailer->sendNoticeInterviewHasBeenCancelledToCandidate($event->interview, $event->previousCandidate);
    }
}
