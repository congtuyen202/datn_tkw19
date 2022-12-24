@extends('layout.index-employ')
@section('content')
    <register-employer
        :data="{{ json_encode([
            'urlStore' => route('register.employer.create'),
            'urlBack' => route('home.index'),
            'location' => $location,
        ]) }}">
    </register-employer>
@endsection