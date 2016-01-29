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

        $this->sendToUser($user,
            'Votre CV a été refusé',
            'emails.refuse-document',
            $data,[], true
        );

        return true;
    }
}