<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    //
    $users = User:: where ;

    $posts = collect([]);

    foreach ($users as $user) {
        $posts->push($user->posts);
    }

    $posts->orderByDesc('created_at');
    getCountLikeAtributes() {
        $this->dsadasdahdk
        return 5;
   
    }
    Post {
        id : $this->id
        user_created : post->user,
        avartar: $this->avatar
        like_count: $this->count_like,
        count_comment: $this->count_ment
    }

    detail <post> {
        
    }
    list_comment: new CommentCollection($this->comments);
}
