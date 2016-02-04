<?php
/**
 * Created by PhpStorm.
 * User: Nicolas
 * Date: 2/4/2016
 * Time: 2:48 PM
 */

namespace CVS\Listeners;

use CVS\Events\CandidateDocumentWasUploaded;
use CVS\Mailer\UserMailer;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailOrganizerCandidateDocumentWasUploaded implements ShouldQueue
{
    use InteractsWithQueue;

    public $userMailer;

    public function __construct(UserMailer $userMailer)
    {
        $this->userMailer = $userMailer;
    }

    public function handle(CandidateDocumentWasUploaded $event)
    {
        $this->userMailer->warnOrganizerPendingDocument();

    }
}