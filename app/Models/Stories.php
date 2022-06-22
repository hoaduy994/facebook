<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stories extends Model
{
    use HasFactory;
    protected $guarded = [];

    protected $table = 'stories_text';
    
    public function storiesCreated() {
        return $this->hasMany(Stories::class, 'user_id', 'id');
    }

}
