<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Like;
use App\Models\Post;
use Illuminate\Support\Facades\Auth;

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

        return redirect()->back();
    }

    public function unlike($id)
    {
        Like::where([
            'user_id' => Auth::id(),
            'post_id' => $id
        ])->delete();

        return redirect()->back();
    }
}

