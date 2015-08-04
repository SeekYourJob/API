@extends('emails.skeleton')

@section('content')
    <strong>{{ $referralFirstname }} {{ $referralLastname }}</strong> vient de s'inscrire pour particpier à la journée de Jobs Dating organisée par l'équipe CVS de la FGES.<br><br>
    Lors de son inscription, {{ $referralFirstname }} a renseigné(e) vos informations afin que vous soyez également inscrit. Un compte vous a donc été automatiquement créé, n'hésitez pas à vous rendre à <a href="{{ env('WEBSITE_URL') }}">l'adresse internet de l'évènement</a> avec vos identifiants précisés ci-dessous; vous y retrouverez tous les détails de cette journée.<br><br>
    <strong>Identifiants :</strong><br>
    Adresse email : {{ $recruiterEmail }}<br>
    Mot de passe : {{ $generatedPassword }}
@endsection