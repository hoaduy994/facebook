<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index() {
        $posts = Post::all()->sortByDesc('created_at');
        return response()->json([
            'message' => 'Get posts successfully',
            'posts' => $posts
        ]);
    }
    
    public function createPost(Request $request) {
        $request->validate([
            'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
        ]);
        $data = [
            'content' => $request->content,
            'access_modifier' => $request->access_modifier
        ];
        // if ($request->access_modified){
        //     $data['access_modified'] = $request->access_modified;
        // }

        $user = auth()->user();
        $post = $user->postsCreated()->create($data);

        return response()->json([
            'message' => 'Created posts successfully',
            'post' => $post
        ]);
        // $post = Post::create
    }

    public function updatePost(Request $request, $id){
        $request->validate([
            'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
        ]);
        // $data = [
        //     'content' => $request->content,
        //     'access_modifier' => $request->access_modifier
        // ];
        

        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $checkAuthor = auth()->user()->postsCreated->where('id', $id)->first();
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission to update post',
            ], 403);
        }

     
        DB::beginTransaction();
        try {
            $post->content =  $request->content;
            $post->access_modifier =  $request->access_modifier;
            $post->save();
            DB::commit();
            return response()->json([
                'message' => 'Updated posts successfully',
                'post' => $post
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }


    public function deletePost(Request $request, $id){
        $post = Post::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $checkAuthor = auth()->user()->postsCreated->where('id', $id)->first();
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission to delete post',
            ], 403);
        }

        DB::beginTransaction();
        try {
            $post->delete();
            DB::commit();
            return response()->json([
                'message' => 'Delete posts successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
}