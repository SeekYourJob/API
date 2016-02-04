<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 2/4/2016
 * Time: 2:41 PM
 */
namespace CVS\Events;

use CVS\Events\Event;
use Illuminate\Queue\SerializesModels;

class CandidateDocumentWasUploaded extends Event
{
    use SerializesModels;

    public function __construct(){}

    public function broadcastOn()
    {
        return [];
    }
}