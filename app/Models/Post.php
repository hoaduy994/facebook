<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'posts';
    
    public function postsCreated() {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function group(){
        return $this->belongsTo(Groups::class);
    }
}
