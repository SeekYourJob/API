@extends('emails.skeleton')

@section('content')
    <strong>{{ $referralFirstname }} {{ $referralLastname }}</strong> de <strong>{{ $referralCompany }}</strong> vient de s'inscrire au Job Forum de la FGES organisé par l'équipe SeekYourJob.<br><br>
    Lors de son inscription, {{ $referralFirstname }} a saisi votre adresse email de sorte que vous soyez prévenu de sa participation et que vous puissiez, à votre tour, vous inscrire pour l'accompagner.<br><br>
    N'hésitez pas à vous rendre à <a href="{{ env('WEBSITE_URL') }}">l'adresse internet de l'évènement</a> où vous retrouverez tous les détails de cette journée et pourrez vous y inscrire en quelques minutes.
@endsection