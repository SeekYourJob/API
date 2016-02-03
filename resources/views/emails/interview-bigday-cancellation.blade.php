@extends('emails.skeleton')

@section('content')
    Nous vous informons que votre entretien avec <strong>{{ $interviewCompanyName }}</strong> prévu aujourd'hui à {{ $interviewBeginsAt }} <strong style="color: red;">a été annulé</strong>.<br><br>
    Nous en sommes sincèrement désolés et espérons que d'autres entretiens vous intéresseront sur <a href="{{ env('WEBSITE_URL') }}">la plateforme dédiée</a>.
@endsection