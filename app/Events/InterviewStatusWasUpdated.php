<?php

namespace CVS\Events;

use CVS\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InterviewStatusWasUpdated extends Event implements shouldBroadcast
{
    use SerializesModels;

    public function __construct()
    {
        //
    }

    public function broadcastOn()
    {
        return ['live-interviews'];
    }

    public function broadcastAs()
    {
        return 'interviews-updated';
    }
}
