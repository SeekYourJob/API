<?php

namespace CVS\Events;

use CVS\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ResumeWasRefused extends Event
{
    use SerializesModels;

    public function __construct()
    {
        //
    }

    public function broadcastOn()
    {
        return [];
    }
}
