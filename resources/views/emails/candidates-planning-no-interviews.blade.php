@extends('emails.skeleton')

@section('content')
Le Job Forum organisé par la filière informatique de la FGES approche à grand pas...

Sauf erreur de notre part, vous n'avez pas encore planifié d'entretien pour cette journée ! Si ce n'est pas déjà fait, nous vous invitons à nous transmettre votre CV via <a href="{{ env('WEBSITE_URL') }}">le site internet de l'évènement</a>.<br/><br/>
Une fois celui-ci validé par notre équipe, vous pourrez réserver les créneaux qui vous intéressent le plus. <br/><br/>
Aussi, vous n'êtes peut-être pas à l'écoute d'opportunités professionelles pour le moment mais nous pensons que cet évènement est une belle opportunité pour vous étudiants de faire rayonner notre Faculté auprès des nombreuses entreprises participantes.<br/><br/>
Votre participation active est donc cruciale !

Nous restons à votre disposition si vous avez la moindre question.
@endsection