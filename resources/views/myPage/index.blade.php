@extends('layouts.user')

@section('content')
    <my-page
        :data="{{ json_encode([
            'urlEarn' => route('myPage.earn'),
        ]) }}">
    </my-page>
@endsection
