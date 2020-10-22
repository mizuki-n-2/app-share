<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store($id, Request $request)
    {
        $validatedData = $request->validate([
            'comment' => 'required',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'post_id' => $id,
            'comment' => $request->comment
        ]);

        return redirect()->back();
    }
}
