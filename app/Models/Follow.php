<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    use HasFactory;

    protected $fillable = ['target_id'];
    protected $table = ['users'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function follows() {
        return $this->hasMany(Relations::class);
    }
    
    public function isFollowing($friend_id)
    {
        return $this->follows()->where('friend_id', $friend_id)->first(['id']);
    }
}
