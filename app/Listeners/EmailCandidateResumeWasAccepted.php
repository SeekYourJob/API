<?php

namespace CVS\Listeners;

use CVS\Events\ResumeWasAccepted;
use CVS\Mailer\CandidateMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCandidateResumeWasAccepted implements shouldQueue
{
    use InteractsWithQueue;

    public $candidateMailer;

    public function __construct(CandidateMailer $candidateMailer)
    {
        $this->candidateMailer = $candidateMailer;
    }

    public function handle(ResumeWasAccepted $event)
    {
        $this->candidateMailer->acceptCandidateDocument($event->document);
    }
}
