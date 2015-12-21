@extends('emails.skeleton')

@section('content')
    Ci-dessous votre planning pour le Job Forum Vous trouverez en pièce jointe les CVs des étudiants inscrits :
    {{usort($interviews, function($a,$b){ return strtotime($a["slot"]["begins_at"])-strtotime($b["slot"]["begins_at"]);})}}
    <ul>
        @foreach ($interviews as $interview)
            <li>
              {{ date('H:i', strtotime($interview["slot"]["begins_at"])) }} &rarr; {{ date('H:i', strtotime($interview["slot"]["ends_at"])) }} : <strong>{{$interview["candidate"]["user"]["firstname"]}} {{$interview["candidate"]["user"]["lastname"]}}</strong>
            </li>
        @endforeach
    </ul>
    N'hésitez pas à nous contacter si vous nécessitez des informations complémentaires.
@endsection