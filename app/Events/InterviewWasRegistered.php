<?php

namespace CVS\Events;

use CVS\Events\Event;
use CVS\Interview;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InterviewWasRegistered extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $interview;

    public function __construct(Interview $interview)
    {
        $this->interview = $interview;
    }

    public function broadcastOn()
    {
        return ['presence-interviews'];
    }

    public function broadcastAs()
    {
        return 'interviews-updated';
    }

    public function broadcastWith()
    {
        return [];
    }
}
