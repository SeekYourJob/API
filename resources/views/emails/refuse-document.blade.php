@extends('emails.skeleton')

@section('content')
    Votre CV <strong>{{ $documentName }}</strong> a été refusé par les administrateurs de l'équipe SeekYourJob.<br><br>
    Nous vous rappelons qu'il est impératif de nous transmettre un CV valide pour pouvoir vous inscrire à des entretiens.<br><br>
    N'hésitez pas à vous rendre sur <a href="{{ env('WEBSITE_URL') }}">le site SeekYourJob</a> pour envoyer un nouveau CV.
@endsection