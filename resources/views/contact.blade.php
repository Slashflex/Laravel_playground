@extends('layout')

@section('content')
    <h1>Contact</h1>
    <p>Hello this is contact</p>

    @can('home.secret')
        <p>
            <a href="{{ route('secret') }}">
                Got to special contact details!
            </a>
        </p>
    @endcan
@endsection