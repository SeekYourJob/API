@extends('emails.skeleton')

@section('content')
    Nous vous confirmons votre entretien du Job Forum avec <strong>{{ $interviewCompanyName }}</strong> prévu à <strong>{{ $interviewBeginsAt }}</strong>.<br><br>
    Si vous ne pensez pas pouvoir vous présenter à l'entretien, merci de le signaler le plus rapidement possible via <a href="{{ env('WEBSITE_URL') }}">la plateforme dédiée</a>.
@endsection