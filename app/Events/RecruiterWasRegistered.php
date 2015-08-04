<?php

namespace CVS\Events;

use CVS\Events\Event;
use CVS\Recruiter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RecruiterWasRegistered extends Event
{
    use SerializesModels;

    public $recruiter;

    public function __construct(Recruiter $recruiter)
    {
        $this->recruiter = $recruiter;
    }

    public function broadcastOn()
    {
        return [];
    }
}
