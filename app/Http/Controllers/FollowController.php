<?php

namespace App\Http\Controllers;

use App\Models\Follow;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class FollowController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function follow($id)
    {
        Follow::create([
            'user_id' => Auth::id(),
            'follow_id' => $id
        ]);

        $now = Carbon::now();
        $date_time = date('n/j G:i', strtotime($now));

        Notification::create([
            'user_id' => $id,
            'by_user_name' => Auth::user()->name,
            'date_time' => $date_time,
        ]);

        session()->flash('flash_message', 'フォローしました');

        return redirect()->back();
    }

    public function unfollow($id)
    {
        Follow::where([
            'user_id' => Auth::id(),
            'follow_id' => $id
        ])->delete();

        session()->flash('flash_message', 'フォローを解除しました');

        return redirect()->back();
    }
}
