<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\Like;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest');
    }
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $sub_query = Like::select("post_id", DB::raw('count(*) as like_number'))->groupBy('post_id');

        $posts = Post::join(DB::raw("({$sub_query->toSql()}) AS filtered_likes"),'posts.id', '=', 'filtered_likes.post_id')->orderBy('filtered_likes.like_number', 'desc')->paginate(6);

        return view('home', ['posts' => $posts]);
    }
}
