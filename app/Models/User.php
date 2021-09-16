<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use App\Notifications\PasswordResetNotification;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'image',
        'profile'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // relation
    public function posts()
    {
        return $this->hasMany('App\Models\Post');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

    public function comments()
    {
        return $this->hasMany('App\Models\Comment');
    }

    public function follows()
    {
        return $this->hasMany('App\Models\Follow');
    }

    public function notifications()
    {
        return $this->hasMany('App\Models\Notification');
    }

    // follow 判定
    public function is_followed_by_auth_user()
    {
        $follow_users = array();
        foreach (Auth::user()->follows as $follow) {
            array_push($follow_users, $follow->follow_id);
        }

        if (in_array($this->id, $follow_users)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * パスワードリセット通知をユーザーに送信
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new PasswordResetNotification($token));
    }
}
