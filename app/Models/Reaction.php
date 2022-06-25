<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    protected $table = 'user_posts';
    public function userCreate()
    {
        return $this->belongsTo(User::class);
    }
   
    public function reaction() {
        return $this->belongsTo(User::class);
    }

    public function user_posts()
    {
        return $this->hasMany(Post::class);
    }
    public function ReactionDetail() {
        return $this->hasMany(PostDetail::class);
    }
    public function ReactionDetailJoin() {
        return $this->belongsTo(PostDetail::class);
    }
}
