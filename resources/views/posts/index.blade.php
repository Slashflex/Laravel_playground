@extends('layout')

@section('content')
    @forelse($posts as $post)
        <p>
            <!-- For New posts -->
            @if ((new Carbon\Carbon())->diffInMinutes($post->created_at) < 5)
                <h3>
                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a><strong> New!</strong>
                </h3>    
            @else 
                <h3>
                    <a href="{{ route('posts.show', ['post' => $post->id]) }}">{{ $post->title }}</a>
                </h3>
            @endif

            <a href="{{ route('posts.edit', ['post' => $post->id]) }}" class="btn btn-primary">Edit</a>
        
            <form method="POST"  class="fm-inline"
                action="{{ route('posts.destroy', ['post' => $post->id]) }}">
                @csrf
                @method('DELETE')
                <input type="submit" value="Delete !" class="btn btn-primary">
            </form>
        </p>
    @empty
        <p>No blog post yet!</p>
    @endforelse
@endsection