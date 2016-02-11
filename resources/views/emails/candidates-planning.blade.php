@extends('emails.skeleton')

@section('content')
    Nous y sommes presque, le Job Forum organisé par la filière informatique de la FGES se tiendra demain ! Nous espérons que vous avez pu réserver des entretiens et que ces derniers se passeront dans les meilleures conditions.<br><br>

    Voici un rappel des entretiens enregistrés :<br>

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

    <br>

    <strong>Quelques informations importantes de dernière minute... :</strong><br><br>
    <ul>
        <li><strong>Les entretiens auront lieu au Rizomm</strong> (soit au Roseau, soit dans les boxs de travail)</li>
        <li><strong>L'accueil du Job Forum se situera dans la salle de pause du Rizomm</strong> (avec un babyfoot). L'équipe y sera présente toute la journée pour répondre à vos questions et vous pourrez y trouver les entretiens en cours également.</li>
        <li>Merci de vous <strong>présenter à l'accueil du Job Forum 5 à 10 minutes avant</strong> le début de vos entretiens.</li>
        <li>Vos CVs ont été transmis aux recruteurs ; libre à eux de les imprimer ou non. <strong>Nous vous conseillons vivement de vous munir de quelques exemplaires papiers au cas où...</strong></li>
        <li>Comme pour tout entretien, <strong>merci d'adopter une tenue correcte</strong> et de <strong>vous renseigner en amont sur les entreprises</strong> avec lesquelles vous avez des créneaux réservés.</li>
        <li>Un papier et un crayon ne peuvent pas faire de mal et vous permettront sans nul doute de <strong>prendre des notes</strong> lors de vos entretiens.</li>
        <li>Enfin, des réserves ont été émises quant aux offres proposées par les entreprises, très typées informatique. Ce ne sont que des exemples et nous sommes convaincus que <strong>la plupart des entreprises recherchent des profils variés</strong> !</li>
    </ul><br><br>

    Nous restons à votre disposition pour toute information complémentaire et espérons que cette journée répondra à vos attentes !
@endsection