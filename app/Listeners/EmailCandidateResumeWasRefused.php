<?php

namespace CVS\Listeners;

use CVS\Events\ResumeWasRefused;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCandidateResumeWasRefused
{

    public function __construct()
    {
        //
    }

    public function handle(ResumeWasRefused $event)
    {
        //
    }
}
