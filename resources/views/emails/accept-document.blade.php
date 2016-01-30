@extends('emails.skeleton')

@section('content')
    Félicitations ! Votre CV "<em>{{ $documentName }}</em>" a été validé par les administrateurs de l'équipe SeekYourJob !<br><br>
    Vous pouvez désormais réserver des entretiens via <a href="{{ env('WEBSITE_URL') }}">la plateforme dédiée.</a>.
@endsection