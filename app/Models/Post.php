<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Post extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'title', 'image', 'content', 'url'
    ];

    protected $guarded = [
        'create_at', 'update_at'
    ];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }

    public function likes()
    {
        return $this->hasMany('App\Models\Like');
    }

    public function is_liked_by_auth_user()
    {
        $id = Auth::id();

        $like_users = array();
        foreach ($this->likes as $like) {
            array_push($like_users, $like->user_id);
        }

        if (in_array($id, $like_users)) {
            return true;
        } else {
            return false;
        }
    }
}
