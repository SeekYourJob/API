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

    public $guzzleClient;
    public $phoneNumber;
    public $message;

    public function __construct($phoneNumber, $message)
    {
        $this->phoneNumber = $phoneNumber;
        $this->message = $message;
    }

    public function handle()
    {
        Texter::doSendToPhoneNumber($this->phoneNumber, $this->message);
    }
}
