<?php

namespace CVS\Listeners;

use CVS\Events\InterviewWasCanceled;
use CVS\Slot;
use CVS\Texter\CandidateTexter;
use Illuminate\Contracts\Queue\ShouldQueue;

class TextCandidateInterviewWasCanceled implements shouldQueue
{
    public $candidateTexter;

    public function __construct(CandidateTexter $candidateTexter)
    {
        $this->candidateTexter = $candidateTexter;
    }

    public function handle(InterviewWasCanceled $event)
    {
        if (Slot::isBigDay())
            $this->candidateTexter->sendNoticeInterviewHasBeenCancelledToCandidate($event->interview, $event->previousCandidate);
    }
}
