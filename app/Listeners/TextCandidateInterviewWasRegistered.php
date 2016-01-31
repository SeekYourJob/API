<?php

namespace CVS\Listeners;

use CVS\Events\InterviewWasRegistered;
use CVS\Slot;
use CVS\Texter\CandidateTexter;
use Illuminate\Contracts\Queue\ShouldQueue;

class TextCandidateInterviewWasRegistered implements shouldQueue
{
    public $candidateTexter;

    public function __construct(CandidateTexter $candidateTexter)
    {
        $this->candidateTexter = $candidateTexter;
    }

    public function handle(InterviewWasRegistered $event)
    {
        if (Slot::isBigDay()) {
            $this->candidateTexter->sendInterviewReminderToCandidate($event->interview);
        }
    }
}
