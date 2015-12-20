<?php

namespace CVS\Listeners;

use CVS\Candidate;
use CVS\Events\ResumeWasRefused;
use CVS\Mailer\CandidateMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCandidateResumeWasRefused implements shouldQueue
{

    use InteractsWithQueue;

    public $candidateMailer;

    public function __construct(CandidateMailer $candidateMailer)
    {
        $this->candidateMailer = $candidateMailer;
    }

    public function handle(ResumeWasRefused $event)
    {
        $this->candidateMailer->refuseCandidateDocument($event->document);
    }
}
