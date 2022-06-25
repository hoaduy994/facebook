<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Storiesimg extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'stories_img';
    
    public function storiesImgCreated() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

}
