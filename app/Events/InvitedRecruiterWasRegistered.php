<?php

namespace CVS\Events;

use CVS\Events\Event;
use CVS\Recruiter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InvitedRecruiterWasRegistered extends Event
{
    use SerializesModels;

    public $referral;
    public $recruiter;
    public $generatedPassword;

    public function __construct(Recruiter $referral, Recruiter $recruiter, $generatedPassword)
    {
        $this->referral = $referral;
        $this->recruiter = $recruiter;
        $this->generatedPassword = $generatedPassword;
    }

    public function broadcastOn()
    {
        return [];
    }
}
