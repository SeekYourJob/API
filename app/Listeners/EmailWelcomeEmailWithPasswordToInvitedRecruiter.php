<?php

namespace CVS\Listeners;

use CVS\Events\InvitedRecruiterWasRegistered;
use CVS\Mailer\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailWelcomeEmailWithPasswordToInvitedRecruiter implements ShouldQueue
{
    public $userMailer;

    public function __construct(UserMailer $userMailer)
    {
        $this->userMailer = $userMailer;
    }

    public function handle(InvitedRecruiterWasRegistered $event)
    {
        $this->userMailer->welcomeInvitedRecruiter($event->referral, $event->recruiter, $event->generatedPassword);
    }
}
