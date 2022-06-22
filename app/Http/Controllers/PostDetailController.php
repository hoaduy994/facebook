<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\Reaction;
use App\Models\PostDetail;

class PostDetailController extends Controller
{
    public function post_detail(Request $request, $id)
    {
       
        $post_id = $id;
        $reaction_detail = PostDetail::find($post_id);

        return response()->json([
            'message' => 'detail',
            'detail' => $reaction_detail
        ]);

    }   
}
