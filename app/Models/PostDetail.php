<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PostDetail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $guarded = [];

    protected $table = 'post_details';

    public function ReactionDetail() {
        return $this->belongsTo(PostDetail::class);
    }
    public function ReactionDetailJoin() {
        return $this->belongsTo(Reaction::class);
    }
    public function ReactionJoinDetail() {
        return $this->hasMany(Post::class);
    }

    public function usercreate(){
        return $this->belongsTo(User::class);
    }

    public function createPostdetail (){
        return $this->belongsTo(Post::class,'post_id');
    }
}
