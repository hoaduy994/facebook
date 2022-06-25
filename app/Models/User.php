<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];
    protected $table = 'users';
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

      /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier() {
        return $this->getKey();
    }
    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims() {
        return [];
    } 

    public function postsCreated() {
        return $this->hasMany(Post::class, 'user_id', 'id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function posts() {
  
        return $this->hasMany(User::class,'user_id', 'id');
     
    }

    public function sendRequest() {
        return $this->hasMany(Relation::class,'friend_id','id');
    }

    public function storiesCreated() {
        return $this->hasMany(Stories::class, 'user_id', 'id');
    }
    
    public function storiesImgCreated() {
        return $this->hasMany(Storiesimg::class, 'user_id', 'id');
    }

    public function groupsCreated() {
        return $this->belongsToMany(Groups::class, 'user_groups','user_id', 'id');
    }
    

    public function friendRequests (){
        return $this->hasMany(Relation::class, 'friend_id')->where('is_friend', Relation::REQUEST_FRIEND);
    }

    
    public function friends (){
        return $this->hasMany(Relation::class, 'friend_id')->where('is_friend', Relation::IS_FRIEND);
    }

    public function groups() {
        return $this->hasMany(Groups::class);
    }
    
    public function members (){
        return $this->hasMany(GroupUser::class, 'user_id')->where('stt', GroupUser::IS_MEMBER);
    }

    
    public function memberRequests (){
        return $this->hasMany(GroupUser::class, 'user_id')->where('stt', GroupUser::REQUEST_MEMBER);
    }  

    public function comments() {
        return $this->hasMany(User::class,'user_id','id');
    }
    public function commentsCreated() {
        return $this->hasMany(Comment::class, 'user_id', 'id');
    }

    

}
