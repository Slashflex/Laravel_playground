<?php

namespace App\Http\Controllers;

use App\BlogPost;
use Illuminate\Http\Request;
use App\Http\Requests\StorePost;
use App\User;
use Illuminate\Support\Facades\Cache;

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
        $mostCommented = Cache::tags(['blog-post'])->remember('blog-post-commented', now()->addSeconds(10), function() {
            return BlogPost::mostCommented()->take(5)->get();
        });

        $mostActive = Cache::remember('users-most-active', now()->addSeconds(10), function() {
            return User::withMostBlogPosts()->take(5)->get();
        });

        $mostActiveLastMonth = Cache::remember('users-most-active-last-month', now()->addSeconds(10), function() {
            return User::withMostBlogPostsLastMonth()->take(5)->get();
        });

        return view(
            'posts.index',
            [
                'posts' => BlogPost::latest()->withCount('comments')->with('user')->get(),
                'mostCommented' => $mostCommented,
                'mostActive' => $mostActive,
                'mostActiveLastMonth' => $mostActiveLastMonth,
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

        $blogPost = Cache::tags(['blog-post'])->remember("blog-post-{$id}", 60, function () use($id) {
            return BlogPost::with('comments')->findOrFail($id);
        });

        $sessionId = session()->getId();
        $counterKey = "blog-post-{$id}-counter";
        $usersKey = "blog-post-{$id}-users";

        $users = Cache::tags(['blog-post'])->get($usersKey, []);
        $usersUpdate = [];
        $difference = 0;
        $now = now();

        foreach ($users as $session => $lastVisit) {
            if ($now->diffInMinutes($lastVisit) >= 1) {
                $difference--;
            } else {
                $usersUpdate[$session] = $lastVisit;
            }
        }

        if (
            !array_key_exists($sessionId, $users)
            || $now->diffInMinutes($users[$sessionId]) >= 1
        ) {
            $difference++;
        }

        $usersUpdate[$sessionId] = $now;
        Cache::tags(['blog-post'])->forever($usersKey, $usersUpdate);
        
        if (!Cache::tags(['blog-post'])->has($counterKey)) {
            Cache::tags(['blog-post'])->forever($counterKey, 1);
        } else {
            Cache::tags(['blog-post'])->increment($counterKey, $difference);
        }

        $counter = Cache::tags(['blog-post'])->get($counterKey);

        return view('posts.show', [
            'post' => $blogPost,
            'counter' => $counter,
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
