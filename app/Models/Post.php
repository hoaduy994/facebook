<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'posts';
    
    // public function postsCreated() {
    //     return $this->belongsTo(Post::class, 'post_id', 'id');
    // }

    // public function postsCreated1() {
    //     return $this->belongsTo(Group::class, 'post_id', 'id');
    // }

    public function group(){
        return $this->belongsTo(Groups::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    public function reaction()
    {
        return $this->hasMany(Reaction::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

// ->whereNull('parent_id')
    public function ReactionDetailJoin() {
        return $this->hasMany(Reaction::class);
    }

    public function createPostdetail (){
        return $this->hasMany(PostDetail::class,'id');
    }
}
