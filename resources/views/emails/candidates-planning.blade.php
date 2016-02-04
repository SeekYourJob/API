@extends('emails.skeleton')

@section('content')
    Ci-dessous votre planning pour le Job Forum :

    @if (usort($interviews, function($a,$b){ return strtotime($a["slot"]["begins_at"])-strtotime($b["slot"]["begins_at"]);}) == 1)
    <ul>
        @foreach ($interviews as $interview)
            <li>
              <strong>{{ date('H:i', strtotime($interview["slot"]["begins_at"])) }} &rarr; {{ date('H:i', strtotime($interview["slot"]["ends_at"])) }} :</strong> {{$interview["recruiter"]["user"]["firstname"]}} {{$interview["recruiter"]["user"]["lastname"]}} - <em>{{$interview["recruiter"]["company"]["name"]}}</em>
            </li>
        @endforeach
    </ul>
    @else
       <strong>Erreur dans la récupération des entretiens</strong>
    @endif

    Votre CV à été envoyé aux personnes concernées, n'oubliez pas néanmoins de venir avec des versions papier le jour J. <br/>
    N'hésitez pas à nous contacter si vous nécessitez des informations complémentaires.
@endsection