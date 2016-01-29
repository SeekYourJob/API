@extends('emails.skeleton')

@section('content')
    Votre document <strong>{{ $documentName }}</strong> a été refusé par les administrateurs de l'équipe SeekYourJob.<br><br>
    Il vous est vivement conseillé d'en uploader un nouveau, sinon celui-ci ne sera pas communiqué aux recruteurs présents le jour J.<br><br>
    Rendez-vous sur  <a href="{{ env('WEBSITE_URL') }}">le site SeekYourJob</a> pour envoyer un nouveau document.
@endsection