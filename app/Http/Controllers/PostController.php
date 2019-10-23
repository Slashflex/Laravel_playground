<?php

namespace App\Http\Controllers;

use App\BlogPost;

// use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')
            ->only(['create', 'store', 'edit', 'update', 'destroy']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // DB::connection()->enableQueryLog();

        // Eager Loading
        // $posts = BlogPost::with('comments')->get();

        // Lazy Loading
        // $posts = BlogPost::all();

        // foreach ($posts as $post)
        // {
        //     foreach ($post->comments as $comment)
        //     {
        //         echo $comment->content;
        //     }
        // }

        // dd(DB::getQueryLog());


        // comments_count
        return view(
            'posts.index',
            ['posts' => BlogPost::withCount('comments')->get()]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, $id)
    {
        return view('posts.show', [
            'post' => BlogPost::with('comments')->findOrFail($id)
        ]);
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validatedDate = $request->validated();
        $blogPost = BlogPost::create($validatedDate);
        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $validatedDate = $request->validated();

        $post->fill($validatedDate);
        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        $post->delete();

        // BlogPost::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
}
