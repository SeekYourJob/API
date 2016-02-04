@extends('emails.skeleton')

@section('content')
    Un document est en attente de validation sur le site <a href="{{ env('WEBSITE_URL') }}">SeekYourJob</a>.
@endsection