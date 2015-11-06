<?php

namespace CVS\Listeners;

use CVS\Events\InterviewWasCanceled;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailCandidateInterviewWasCanceled
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InterviewWasCanceled  $event
     * @return void
     */
    public function handle(InterviewWasCanceled $event)
    {
        //
    }
}
