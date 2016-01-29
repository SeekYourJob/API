@extends('emails.skeleton')

@section('content')
    {{ $referralFirstname }} {{ $referralLastname }} vient de s'inscrire au Job Forum de la FGES organisé par l'équipe SeekYourJob.<br><br>
    Lors de son inscription, {{ $referralFirstname }} a renseigné vos informations afin que vous soyez également inscrit. Un compte vous a donc été automatiquement créé.<br><br>
    N'hésitez pas à vous rendre à <a href="{{ env('WEBSITE_URL') }}">l'adresse internet de l'évènement</a> avec vos identifiants précisés ci-dessous ; vous y retrouverez tous les détails de cette journée.<br><br>
    <strong>Identifiants :</strong><br>
    Adresse email : {{ $recruiterEmail }}<br>
    Mot de passe  : {{ $generatedPassword }}
@endsection