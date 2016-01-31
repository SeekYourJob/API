<?php

namespace CVS\Events;

use CVS\Candidate;
use CVS\Events\Event;
use CVS\Interview;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class InterviewWasCanceled extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $interview;
    public $previousCandidate;

    public function __construct(Interview $interview, Candidate $previousCandidate)
    {
        $this->interview = $interview;
        $this->previousCandidate = $previousCandidate;
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
