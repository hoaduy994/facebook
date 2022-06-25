<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use App\Models\CommentUser;

class CommentController extends Controller
{
    public function createComment(Request $request, $post_id)
    {
        $request->validate([
            'content'=>'required',
            'img' => 'image'
        ],[
            'content.required'=>'Nội dung không được bỏ trống.',
        ]);
        $user_id = Auth::user()->id;
        // dd($user_id);
        $data = [
            'content' => $request->content,
            'user_id' =>  $user_id,
            'post_id' => $post_id,
        ];

        // dd($data);
        $comment = Comment::create($data);

        return response()->json([
            'message' => 'User successfully commentted',
            'comment' => $comment,
            
        ]);
   
    }
    public function updateComment(Request $request, $id)
    {
        $request->validate([
            'content'=>'required',
        ]);
        // dd($request);
        $comment = Comment::find($id);


        $checkAuthor = auth()->user()->commentsCreated->where('id', $id)->first();;
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission to update comment',
            ], 403);
        }
        DB::beginTransaction();
        try {
            $comment->content =  $request->content;
            $comment->save();
            DB::commit();
            return response()->json([
                'message' => 'Updated comment successfully',
                'comment' => $comment
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    public function deleteComment(Request $request, $id){
        $comment = Comment::find($id);
        
        $checkAuthor = auth()->user()->commentsCreated->where('id', $id)->first();
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission to delete comment',
            ], 403);
        }

        DB::beginTransaction();
        try {
            
            $comment->delete();
            DB::commit();
            return response()->json([
                'message' => 'Delete comment successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}

