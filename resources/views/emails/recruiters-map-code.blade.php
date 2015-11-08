@extends('emails.skeleton')

@section('content')
    Le Job Forum de la FGES approche à grand pas !<br><br>

    Ci-dessous, vous trouverez le plan d'accès au parking auquel vous pourrez accéder le jour de l'évènement. L'entrée du parking se situe au <a href="https://goo.gl/maps/hHe52kshLS92"><strong>5 rue Roland</strong></a> tandis que l'entrée de la Faculté au <a href="https://goo.gl/maps/HDdnBmcEesJ2"><strong>41 rue du Port</strong></a> à Lille.<br><br>

    Un code d'accès vous sera demandé, le voici : <strong>1234</strong>.<br><br>

    <img src="{{ $message->embed(public_path('img/gmaps.jpg')) }}" /><br><br>

    Nous vous remercions à nouveau de votre participation !<br>
@endsection