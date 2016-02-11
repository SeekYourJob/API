@extends('emails.skeleton')

@section('content')
    Le Job Forum de la FGES approche à grand pas !<br><br>

    Ci-dessous, le plan d'accès au parking accessible le jour de l'évènement. Celui-ci étant en rénovation le nombre de places est limité. Aussi, nous vous invitons à minimiser le nombre de véhicules dans la mesure du possible.<br><br>

    L'entrée du parking se situe au <a href="https://goo.gl/maps/hHe52kshLS92"><strong>5 rue Roland</strong></a> tandis que l'entrée de la Faculté au <a href="https://goo.gl/maps/HDdnBmcEesJ2"><strong>41 rue du Port</strong></a> à Lille.<br><br>

    <strong>Aucun code n'est nécessaire</strong>, il vous suffira de vous présenter en tant que participant au Job Forum de la FGES à l'entrée pour accéder au parking. Un membre de l'équipe s'assurera néanmoins de votre bonne arrivée.<br><br>

    <img src="{{ $message->embed(public_path('img/gmaps.jpg')) }}" /><br><br>

    Nous vous remercions à nouveau de votre participation !<br>
@endsection