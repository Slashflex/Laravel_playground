@extends('layout')

@section('content')
    <form method="post" action="{{ route('posts.store') }}">
        @csrf
        
        @include('posts._form')

        <button type="submit" class="btn btn-primary btn-block">Create !</button>
    </form>

@endsection