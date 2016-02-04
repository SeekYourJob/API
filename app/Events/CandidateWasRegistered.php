<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 1/29/2016
 * Time: 9:11 AM
 */
namespace CVS\Events;

use CVS\Events\Event;
use CVS\Candidate;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CandidateWasRegistered extends Event
{
    use SerializesModels;

    public $candidate;

    public function __construct(Candidate $candidate)
    {
        $this->candidate = $candidate;
    }

    public function broadcastOn()
    {
        return [];
    }
}