<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::orderBy('created_at', 'desc')->paginate(6);
        return view('post.index', ['posts' => $posts]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('post.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|file|image',
            'url' => 'nullable|url',
            'content' => 'required|max:255',
        ]);

        $id = Auth::id();

        $image = $request->file('image');

        if ($image !== null) {
            $path = Storage::disk('s3')->putFile('/', $image, 'public');
            $image = Storage::disk('s3')->url($path);
        }

        Post::create([
            'user_id' => $id,
            'title' => $request->title,
            'image' => $image,
            'url' => $request->url,
            'content' => $request->content,
        ]);

        session()->flash('flash_message', '投稿が完了しました');

        return redirect('/posts');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $post = Post::where('id', $id)->first();
        $comments = Comment::where('post_id', $id)->get();
        return view('post.show', ['post' => $post, 'comments' => $comments]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $post = Post::where('id', $id)->first();
        return view('post.edit', ['post' => $post]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'title' => 'required|max:255',
            'image' => 'nullable|file|image',
            'url' => 'nullable|url',
            'content' => 'required|max:255',
            'default' => 'nullable'
        ]);

        if ($request->default === 'checked') {
            Post::where('id', $id)->update([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'url' => $request->url,
                'content' => $request->content,
            ]);
        } else {
            $image = $request->file('image');
    
            if ($image !== null) {
                $path = Storage::disk('s3')->putFile('/', $image, 'public');
                $image = Storage::disk('s3')->url($path);
            }
    
            Post::where('id', $id)->update([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'image' => $image,
                'url' => $request->url,
                'content' => $request->content,
            ]);
        }

        session()->flash('flash_message', '投稿の編集が完了しました');

        return redirect()->route('posts.show', ['post' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::where('id', $id)->first();
        $post->delete();
        if ($post->likes !== null) {
            $post->likes()->delete();
        }
        if ($post->comments !== null) {
            $post->comments()->delete();
        }

        session()->flash('flash_message', '投稿を削除しました');

        return redirect()->route('profile', ['id' => $post->user_id]);
    }
}
