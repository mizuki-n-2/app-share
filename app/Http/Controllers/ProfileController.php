<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;
use App\Models\User;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $posts = Post::where('user_id', $user->id)->orderBy('created_at', 'desc')->paginate(6);

        $favorite_posts = Post::select('posts.*')->join('likes', 'likes.post_id', '=', 'posts.id')->where('likes.user_id', $user->id)->orderBy('likes.created_at', 'desc')->paginate(6);
        
        return view('profile', ['posts' => $posts, 'user' => $user, 'favorite_posts' => $favorite_posts]);
    }

    public function edit() 
    {
        $user = Auth::user();
        return view('edit', ['user' => $user]);
    }

    public function update(Request $request)
    {
        $id = Auth::id();

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

        return redirect('profile');
    }
}
