<?php

namespace CVS\Jobs;

use CVS\Jobs\Job;
use CVS\Texter\Texter;
use CVS\User;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendTextToUser extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    public $user;
    public $message;

    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function handle()
    {
        $texter = new Texter();
        $texter->sendToUser($this->user, $this->message);
    }
}
