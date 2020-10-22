<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use App\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('index');
    }

    public function index($id)
    {
        $user = User::where('id', $id)->first();
        $posts = Post::where('user_id', $id)->orderBy('created_at', 'desc')->paginate(6);

        $favorite_posts = Post::select('posts.*')->join('likes', 'likes.post_id', '=', 'posts.id')->where('likes.user_id', $id)->orderBy('likes.created_at', 'desc')->paginate(6);

        return view('profile', ['user' => $user, 'posts' => $posts, 'favorite_posts' => $favorite_posts]);

    }

    public function edit($id) 
    {
        $user = User::where('id', $id)->first();
        return view('edit', ['user' => $user]);
    }

    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required',
            'image' => 'nullable|file|image',
            'profile' => 'nullable'
        ]);

        $image = $request->file('image');

        if ($image !== null) {
            $path = $image->store('public/image');
            $image = basename($path);
        }

        User::where('id',$id)->update([
            'name' => $request->name,
            'image' => $image,
            'profile' => $request->profile
        ]);

        return redirect()->route('profile', ['id' => $id]);
    }
}
