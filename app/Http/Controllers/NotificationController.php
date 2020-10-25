<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function getData() 
    {
        $notifications = Notification::where('user_id',Auth::id())->orderBy('created_at','desc')->get();
        $json = ['notifications' => $notifications];
        return response()->json($json);
    }
}
