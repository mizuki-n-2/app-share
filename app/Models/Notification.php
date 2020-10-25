<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'like_post_title', 'comment_post_title', 'by_user_name', 'date_time'
    ];

    protected $guarded = [
        'create_at', 'update_at'
    ];

    // relation
    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}
