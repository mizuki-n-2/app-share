<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Post;
use App\Models\Notification;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($id, Request $request)
    {
        $validatedData = $request->validate([
            'comment' => 'required|max:255',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $id,
            'comment' => $request->comment
        ]);

        $post_user_id = Post::find($id)->user->id;
        $comment_post_title = Post::find($id)->title;

        $now = Carbon::now();
        $date_time = date('n/j G:i', strtotime($now));

        Notification::create([
            'user_id' => $post_user_id,
            'comment_post_title' => $comment_post_title,
            'by_user_name' => Auth::user()->name,
            'date_time' => $date_time
        ]);

        return redirect()->back();
    }
}
