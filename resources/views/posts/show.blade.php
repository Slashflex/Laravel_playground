@extends('layout')

@section('content')
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->content }}</p>

    <p>Added {{ $post->created_at->diffForHumans() }}</p>
@endsection