@extends('emails.skeleton')

@section('content')
    Ci-dessous votre planning pour le Job Forum :

    @if (usort($interviews, function($a,$b){ return strtotime($a["slot"]["begins_at"])-strtotime($b["slot"]["begins_at"]);}) == 1)
    <ul>
        @foreach ($interviews as $interview)
            <li>
              <strong>{{ date('H:i', strtotime($interview["slot"]["begins_at"])) }} &rarr; {{ date('H:i', strtotime($interview["slot"]["ends_at"])) }} :</strong> {{$interview["candidate"]["user"]["firstname"]}} {{$interview["candidate"]["user"]["lastname"]}} - {{$interview["candidate"]["grade"]}}
            </li>
        @endforeach
    </ul>
    @else
       <strong>Erreur dans la récupération des entretiens</strong>
    @endif
    Vous trouverez en pièce jointe les CVs des étudiants inscrits.
    N'hésitez pas à nous contacter si vous nécessitez des informations complémentaires.
@endsection