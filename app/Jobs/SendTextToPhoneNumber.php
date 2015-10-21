<?php

namespace CVS\Jobs;

use CVS\Jobs\Job;
use CVS\Texter\Texter;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTextToPhoneNumber extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $phoneNumber;
    public $message;
    public $historyId;

    public function __construct($phoneNumber, $message, $historyId)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
        $this->historyId = $historyId;
    }

    public function handle()
    {
        Texter::doSendToPhoneNumber($this->phoneNumber, $this->message, $this->historyId);
    }
}
