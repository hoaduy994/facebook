<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'stories_text';
    
    public function userCreated() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function images(){
        return $this->hasMany(Storiesimg::class,'id');
    }
}
