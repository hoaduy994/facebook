<?php
  
namespace App\Models;
  
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
  
class CommentUser extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
    protected $table = 'user_comments';
   
    public function user()
    {
        return $this->belongsTo(User::class);
    }
   
    public function postComment()
    {
        return $this->belongsTo(Post::class);
    }

    public function comment() {
        return $this->belongsToMany(CommentUser::class,'comments','comment_id','id');
    }

}