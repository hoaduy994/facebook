<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'posts';
<<<<<<< HEAD
    
    public function postsCreated() {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function group(){
        return $this->belongsTo(Groups::class);
=======
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reaction()
    {
        return $this->hasMany(Reaction::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
    public function ReactionDetailJoin() {
        return $this->hasMany(Reaction::class);
    }

    public function createPostdetail (){
        return $this->hasMany(PostDetail::class,'id');
>>>>>>> fdfd20c57abebeb9d2649198baee34df16ef0fa6
    }
}
