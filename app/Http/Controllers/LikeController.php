<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;
use Carbon\Carbon;

class LikeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function like($id)
    {
        Like::create([
            'user_id' => Auth::id(),
            'post_id' => $id 
        ]);

        $post_user_id = Post::find($id)->user->id;
        $like_post_title = Post::find($id)->title;

        $now = Carbon::now();
        $date_time = date('n/j G:i', strtotime($now));

        Notification::create([
            'user_id' => $post_user_id,
            'like_post_title' => $like_post_title,
            'by_user_name' => Auth::user()->name,
            'date_time' => $date_time
        ]);

        session()->flash('flash_message', 'いいねしました');

        return redirect()->back();
    }

    public function unlike($id)
    {
        Like::where([
            'user_id' => Auth::id(),
            'post_id' => $id
        ])->delete();

        session()->flash('flash_message', 'いいねを削除しました');

        return redirect()->back();
    }
}

