<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Relation extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $guarded = [];
    protected $table = 'relations';

    const NO_FRIEND = 0;
    const IS_FRIEND = 1;
    const REQUEST_FRIEND = 2;

    const REJECT_FRIEND = 0;
    const ACCPET_FRIEND = 1;
    
    public function relations(){
        return $this->belongsTo(User::class);
    }

    public function sendRequest(){
        return $this->belongsTo(User::class);
    }
}
