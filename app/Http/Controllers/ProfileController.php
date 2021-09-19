<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;
use App\Models\Like;
use App\Models\Follow;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Storage;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index($id)
    {
        // ユーザー情報
        $user = User::where('id', $id)->first();

        // 投稿
        $returnPosts = [
            'posts.id',
            'posts.user_id',
            'posts.image',
            'posts.title',
            'users.name AS user_name',
            'users.image AS user_image'
        ];

        $posts = Post::select($returnPosts)->leftJoin('users', 'posts.user_id', '=', 'users.id')->where('posts.user_id', $id)->orderBy('posts.created_at', 'desc')->get();

        // いいねした投稿
        $like_posts = Post::select($returnPosts)->join(DB::raw("(SELECT * FROM likes WHERE user_id = $id) AS filtered_likes"),'posts.id', '=', 'filtered_likes.post_id')->leftJoin('users', 'posts.user_id', '=', 'users.id')->orderBy('filtered_likes.created_at', 'desc')->get();

        // フォロー
        $follows = Follow::where('user_id', $id)->orderBy('created_at', 'desc')->get();
        $follow_users = array();
        foreach ($follows as $follow) {
            $follow_user = User::where('id', $follow->follow_id)->first(); 
            array_push($follow_users, $follow_user);
        }

        // フォロワー
        $followeds = Follow::where('follow_id', $id)->orderBy('created_at', 'desc')->get();
        $followed_users = array();
        foreach ($followeds as $followed) {
            $followed_user = User::where('id', $followed->user_id)->first(); 
            array_push($followed_users, $followed_user);
        }

        return view('user.profile', [
            'user' => $user,
            'posts' => $posts,
            'like_posts' => $like_posts,
            'follow_users' => $follow_users,
            'followed_users' => $followed_users,
        ]);
    }

    public function edit($id)
    {
        $user = User::where('id', $id)->first();
        return view('user.edit', ['user' => $user]);
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|max:20',
            'image' => 'nullable|file|image',
            'profile' => 'nullable|max:255',
            'default' => 'nullable'
        ]);

        // TODO: もっといい方法を考える
        // 現状はチェックの外し忘れが怖いので画像が送られてきた場合は更新を優先
        if (empty($request->file('image')) && $request->default === 'checked') {
            User::where('id', $id)->update([
                'name' => $request->name,
                'profile' => $request->profile
            ]);
        } else {
            $image = $request->file('image');
    
            if (isset($image)) {
                $path = Storage::disk('s3')->putFile('/', $image, 'public');
                $image = Storage::disk('s3')->url($path);
            }
    
            User::where('id', $id)->update([
                'name' => $request->name,
                'image' => $image,
                'profile' => $request->profile
            ]);
        }

        session()->flash('flash_message', 'プロフィールの編集が完了しました');

        return redirect()->route('profile', ['id' => $id]);

    }
}
