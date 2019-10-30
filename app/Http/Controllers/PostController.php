<?php

namespace App\Http\Controllers;

use App\BlogPost;

// use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\StorePost;
use App\User;


// [
//     'show' => 'view',
//     'create' => 'create',
//     'store' => 'create',
//     'edit' => 'update',
//     'update' => 'update',
//     'destroy' => 'delete'
// ]
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
            [
                'posts' => BlogPost::latest()->withCount('comments')->get(),
                'mostCommented' => BlogPost::mostCommented()->take(5)->get(),
                'mostActive' => User::withMostBlogPosts()->take(5)->get(),
                'mostActiveLastMonth' => User::withMostBlogPostsLastMonth()->take(5)->get(),
            ]
        );
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Sorts comments by date
        // return view('posts.show', [
        //     'post' => BlogPost::with(['comments' => function ($query) {
        //         return $query->latest();
        //     }])->findOrFail($id)
        // ]);
        return view('posts.show', [
            'post' => BlogPost::with('comments')->findOrFail($id)
        ]);
    }

    public function create()
    {
        // $this->authorize('posts.create');
        return view('posts.create');
    }

    public function store(StorePost $request)
    {
        $validatedDate = $request->validated();
        $validatedDate['user_id'] = $request->user()->id;
        $blogPost = BlogPost::create($validatedDate);
        $request->session()->flash('status', 'Blog post was created!');

        return redirect()->route('posts.show', ['post' => $blogPost->id]);
    }

    public function edit($id)
    {
        $post = BlogPost::findOrFail($id);
        $this->authorize($post);

        // if (Gate::denies('update-post', $post)) {
        //     abort(403, 'You can\'t edit this blog post');
        // }
        
        return view('posts.edit', ['post' => $post]);
    }

    public function update(StorePost $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        
        $this->authorize($post);

        // if (Gate::denies('update-post', $post)) {
        //     abort(403, 'You can\'t edit this blog post');
        // }
        
        $validatedDate = $request->validated();

        $post->fill($validatedDate);
        $post->save();
        $request->session()->flash('status', 'Blog post was updated!');

        return redirect()->route('posts.show', ['post' => $post->id]);
    }

    public function destroy(Request $request, $id)
    {
        $post = BlogPost::findOrFail($id);
        
        // if (Gate::denies('delet-post', $post)) {
        //     abort(403, 'You can\'t delete this blog post');
        // }
        $this->authorize($post);        

        $post->delete();

        // BlogPost::destroy($id);

        $request->session()->flash('status', 'Blog post was deleted!');

        return redirect()->route('posts.index');
    }
}
