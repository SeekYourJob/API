<?php

namespace CVS\Listeners;

use CVS\Events\RecruiterWasRegistered;
use CVS\Mailer\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailWelcomeEmailToRecruiter implements ShouldQueue
{
    use InteractsWithQueue;

    public $userMailer;

    public function __construct(UserMailer $userMailer)
    {
        $this->userMailer = $userMailer;
    }

    public function handle(RecruiterWasRegistered $event)
    {
        $this->userMailer->welcomeRecruiter($event->recruiter);
    }
}
