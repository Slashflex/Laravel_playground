@if ($post->comments_count)
    <p>{{ $post->comments_count }} comments</p>
@else
    <p>No comments yet !</p>
@endif