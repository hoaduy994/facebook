<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\CommentUser;
use App\Models\Post;

use App\Models\PostDetail;
use App\Models\Reaction;
use App\Models\Relation;
use App\Models\Stories;
use App\Models\Storiesimg;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class HomeController extends Controller
{
    
    public function index() {

        $checkAuthor = auth()->user();
        // $user = Post::()->toArray();
        
        $myposts = Post::with('user','comments','reaction','comments.user')
        ->whereIn('user_id', $checkAuthor)
        ->get()->sortByDesc('created_at');

        $friend = User::whereHas('friends', function($q) {
            $q->where('user_id', auth()->user()->id);
        })->get('id')->toArray();
        // dd($friend);

        $posts = Post::with('user','comments','reaction','comments.user')
        ->whereIn('user_id', $friend)
        ->get()->sortByDesc('created_at');

        return response()->json([
            'message' => 'Get posts successfully',
            'mypost' => $myposts,
            'posts' => $posts,
        ]);
    }

    public function listStories()
    {
        $friends = User::whereHas('friends', function($q) {
        $q->where('user_id', auth()->user()->id);
        })->get('id');

        $stories = Stories::with('userCreated','images')->whereIn('user_id', $friends)->get();
        
        return response()->json([
            'stories' => $stories,
            // 'stories_img' => $stories_img,
        ]);
    }

    public function saveImagePost($image){
        $imageName =  uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/employees/Post_home', $imageName);
        return $imageName;
    }

    public function saveImageStories($image){
        $imageName =  uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/employees/stories', $imageName);
        return $imageName;
    }
    
    public function createPost(Request $request) {
        $request->validate([
            'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
            'image' => 'image',
        ]);
        // dd($request->image);
        $data = [
            'content' => $request->content,
            'access_modifier' => $request->access_modifier,
            'image' => $this->saveImagePost($request->image),
        ];
        // dd($data);
        // if ($request->access_modified){
        //     $data['access_modified'] = $request->access_modified;
        // }
        $user = auth()->user();
        $post = $user->postsCreated()->create($data);
        
        $post_id = $post->id;
        $data1= [
            'post_id' => $post_id,
            'is_like' => '0',
            'is_love' => '0',
            'is_haha' => '0',
            'is_angry' => '0',
            'is_sad' => '0',
            'is_wow' => '0',
            'total' => '0',
        ];
        $post_detail = $post->createPostdetail()->create($data1);
        
        return response()->json([

            'message' => 'Bạn đã tạo bài viết thành công.',
            'post' => $post,
            'id' => $post_detail
        ]);
        // $post = Post::create
    }

    public function updatePost(Request $request, $id){
        $request->validate([
            'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
            'image' => 'image',
        ]);


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
            $post->image =  $this->saveImagePost($request->image);
            $post->save();
            DB::commit();
            return response()->json([
                'message' => 'Bạn đã cập nhập bài viết thành công.',
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
                'message' => 'Bạn đã xóa bài viết.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function createStories(Request $request) {
        $request->validate([
            'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
            // 'image' => 'required|image',
        ]);

        $data = [
            'content' => $request->content,
            'access_modifier' => $request->access_modifier,
            // 'image' => $request->image,
        ];
        // if ($request->access_modified){
        //     $data['access_modified'] = $request->access_modified;
        // }

        $user = auth()->user();
        $stories = $user->storiesCreated()->create($data);

        return response()->json([
            'message' => 'Created stories successfully',
            'post' => $stories,
        ]);
        // $post = Post::create
    }

    public function deleteStoriesText(Request $request, $id){
        $post = Stories::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $checkAuthor = auth()->user()->storiesCreated()->where('id', $id)->first();
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
                'message' => 'Delete story successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function createStoriesImg(Request $request) {
        $request->validate([
            // 'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
            'image' => 'required|image',
        ]);

        $data = [
            // 'content' => $request->content,
            'access_modifier' => $request->access_modifier,
            'image' => $this->saveImageStories($request->image),
        ];
        // if ($request->access_modified){
        //     $data['access_modified'] = $request->access_modified;
        // }

        $user = auth()->user();
        $stories = $user->storiesImgCreated()->create($data);

        return response()->json([
            'message' => 'Created stories successfully',
            'post' => $stories,
        ]);
        // $post = Post::create
    }

    public function deleteStoriesImg(Request $request, $id){
        $post = Storiesimg::find($id);
        if (!$post) {
            return response()->json([
                'message' => 'Post not found',
            ], 404);
        }

        $checkAuthor = auth()->user()->storiesImgCreated()->where('id', $id)->first();
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
                'message' => 'Delete story successfully',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

}
