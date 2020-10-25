<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'follow_id'
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
