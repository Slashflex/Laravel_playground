<div class="form-group">
    <label>Title</label>
    <input type="type" name="title" class="form-control"
        value="{{ old('title', $post->title ?? null) }}">
</div>

<div class="form-group">
    <label>Content</label>
    <input type="type" name="content" class="form-control"
        value="{{ old('content', $post->content ?? null) }}">
</div>

@if ($errors->any())
    <div>
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif