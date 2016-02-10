@extends('emails.skeleton')

@section('content')
    Nous avons le plaisir de vous communiquer <strong>votre planning pour le Job Forum</strong> de la filière informatique de la FGES qui se tiendra ce vendredi 12 février :<br><br>

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
    <br><br>
    Celui-ci n'est toutefois pas définitif, les inscriptions étant possibles jusque la veille de l'évènement certains créneaux sont suceptibles d'être libres au moment de l'envoi de cet email.<br><br>

    D'autre part, <strong>vous trouverez en pièce jointe les CVs des étudiants</strong> inscrits pour le moment.<br><br>

    N'hésitez pas à vous connecter sur le <a href="{{ env('WEBSITE_URL') }}">site de l'évènement</a> pour consulter les dernières mises à jour ou à nous contacter pour la moindre information.
@endsection