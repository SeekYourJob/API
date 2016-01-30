<?php

namespace CVS\Events;

use CVS\Document;
use CVS\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ResumeWasAccepted extends Event
{
    use SerializesModels;

    public $document;

    public function __construct(Document $document)
    {
        $this->document = $document;
    }

    public function broadcastOn()
    {
        return [];
    }
}
