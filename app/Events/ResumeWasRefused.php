<?php

namespace CVS\Events;

use CVS\Document;
use Illuminate\Queue\SerializesModels;

class ResumeWasRefused extends Event
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
