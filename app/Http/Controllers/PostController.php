<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\Like;
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
    public function index(Request $request)
    {
        $orderBy = $request->orderBy;

        $returnPosts = [
            'posts.id',
            'posts.user_id',
            'posts.image',
            'posts.title',
            'users.name AS user_name',
            'users.image AS user_image'
        ];
        
        if($orderBy == "popular") {
            $sub_query = Like::select("post_id", DB::raw('count(*) as like_number'))->groupBy('post_id');

            $posts = Post::select($returnPosts)->leftJoin(DB::raw("({$sub_query->toSql()}) AS filtered_likes"),'posts.id', '=', 'filtered_likes.post_id')->leftJoin('users', 'posts.user_id', '=', 'users.id')->orderBy('filtered_likes.like_number', 'desc')->paginate(6);
        }
        else if($orderBy == "new") {
            $posts = Post::select($returnPosts)->leftJoin('users', 'posts.user_id', '=', 'users.id')->orderBy('posts.created_at', 'desc')->paginate(6);
        }
        else {
            return redirect('/posts?orderBy=new');
        }

        $posts->appends(['orderBy' => $orderBy]);
        
        return view('post.index', ['posts' => $posts, 'orderBy' => $orderBy]);
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

        if (isset($image)) {
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

        // TODO: もっといい方法を考える
        // 現状はチェックの外し忘れが怖いので画像が送られてきた場合は更新を優先
        if (empty($request->file('image')) && $request->default === 'checked') {
            Post::where('id', $id)->update([
                'user_id' => Auth::id(),
                'title' => $request->title,
                'url' => $request->url,
                'content' => $request->content,
            ]);
        } else {
            $image = $request->file('image');
    
            if (isset($image)) {
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
        if (isset($post->likes)) {
            $post->likes()->delete();
        }
        if (isset($post->comments)) {
            $post->comments()->delete();
        }

        session()->flash('flash_message', '投稿を削除しました');

        return redirect()->route('profile', ['id' => $post->user_id]);
    }
}
