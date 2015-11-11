@extends('emails.skeleton')

@section('content')
    Suite à votre demande, merci de cliquer sur le lien ci-dessous pour réinitialiser votre mot de passe pour le site du Job Forum de la FGES.<br><br>

    <a href="{{ env('WEBSITE_URL')  }}/do-reset-password?token={{ $reset_password_token }}">Réinitialiser mon mot de passe</a>
@endsection