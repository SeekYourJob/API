<?php namespace CVS\Mailer;

use CVS\User;
use CVS\Document;

class CandidateMailer extends Mailer
{
    public function refuseCandidateDocument(Document $document)
    {
        $user = $document->user;

        $data = [
            'documentName' => $document->name,
        ];
        \Log::alert('document : '.$document->name);
        \Log::alert('user : '.$user->email);

        $this->sendToUser($user,
            'Votre CV a été refusé',
            'emails.refuse-document',
            $data);

        return true;
    }
}