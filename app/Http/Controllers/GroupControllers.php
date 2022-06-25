<?php

namespace App\Http\Controllers;

use App\Models\Groups;
use App\Models\User;
use App\Models\GroupUser;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PHPUnit\TextUI\XmlConfiguration\Group;

class GroupControllers extends Controller
{
    public function listGroup(){
        $group = Groups::all();
        return response()->json([
            'message' => 'listGroup',
            'group' => $group
        ]);
    }

    public function index($group_id) {
        $groups = Groups::find($group_id); 
            if (!$groups) {
                return response()->json([
                    'message' => 'Error',
                ], 404);
            }

        $user = Auth::user()->id;
        $member = DB::table('user_groups')->where('user_id', $user)
        ->where('group_id', $group_id)
        ->where('stt','1')
        ->first();
        // dd($member);
        $members = User::whereHas('members')->get('id');
        // dd(!$members);

        $group = DB::table('groups')
        ->where('id',$group_id)
        ->first('modifier');
        // dd($group);
        if($group->modifier == '1') {
            $posts = Post::with('user','comments','reaction','comments.user')
            ->whereIn('user_id', $members)
            ->where('group_id',$group_id)
            ->get()->toArray();
            return response()->json([
                'message' => 'Get posts successfully',
                'posts' => $posts
            ]);
        } else {
            if ($member){
                $posts = Post::with('user','comments','reaction','comments.user')
                ->whereIn('user_id', $members)
                ->where('group_id',$group_id)
                ->get()->toArray();
                return response()->json([
                    'message' => 'Get posts successfully',
                    'posts' => $posts
                ]);
            } else {
                return response()->json([
                    'message' => 'Không là thành viên.'
                ]);
            }
        }
       
    }
 
    public function saveImage($image){
        $imageName =  uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/employees/group', $imageName);
        return $imageName;
    }

    public function saveImageBG($image){
        $imageName =  uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/employees/BGgroup', $imageName);
        return $imageName;
    }

    public function saveImagePost($image){
        $imageName =  uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/employees/Post_group', $imageName);
        return $imageName;
    }

    public function createPost(Request $request, $group_id) {

        $request->validate([
            'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
            'image' => 'required|image',
        ],[
            'content.required' =>'Nội dung không được bỏ trống.',
        ]);

        $group = Groups::find($group_id);
        if(!$group) {
            return response()->json([
                'message' => 'error',
            ],500);
        }

        $data = [
            'content' => $request->content,
            'access_modifier' => $request->access_modifier,
            'image' => $this->saveImagePost($request->image),   
            'group_id' => $group->id,
            'user_id' => auth()->user()->id,
        ];
        
        // if ($request->access_modified){
        //     $data['access_modified'] = $request->access_modified;
        // }

        $post = Post::create($data);
        
        return response()->json([
            'message' => 'Đã tạo bài viết thành công.',
            'post' => $post
        ]);
        // $post = Post::create
    }

    public function updatePost(Request $request, $group_id, $id) {

        $request->validate([
            'content' => 'required|string',
            'access_modifier' => 'in:1,2,3',
            'image' => 'required|image',
        ],[
            'content.required' =>'Nội dung không được bỏ trống.',
        ]);

        $group = Groups::find($group_id);
        if(!$group) {
            return response()->json([
                'message' => 'error',
            ],500);
        }

        // $data = [
        //     'content' => $request->content,
        //     'access_modifier' => $request->access_modifier,
        //     'image' => $request->image,   
        //     'group_id' => $group->id,
        //     'user_id' => auth()->user()->id,
        // ];
        
        // if ($request->access_modified){
        //     $data['access_modified'] = $request->access_modified;
        // }
        

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
            $post->image = $this->saveImagePost($request->image);
            $post->save();
            DB::commit();
            return response()->json([
                'message' => 'Cập nhập bài viết thành công.',
                'post' => $post
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function deletePost(Request $request, $group_id, $id){
        
        $group = Groups::find($group_id);
        if(!$group) {
            return response()->json([
                'message' => 'error',
            ],500);
        }
        
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
                'message' => 'Đã xóa bài viết.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function createGroup(Request $request) {
        $request->validate([
            'group_name' => 'required|string',
            'modifier' => 'in:1,2',
            // 'image_group' => 'required|image',
            // 'background_image_group' => 'required|image',
        ],[
            'group_name.required' => 'Tên nhóm không được bỏ trống.',
        ]);

        $data = [
            'group_name' => $request->group_name,
            'modifier' => $request->modifier,
            // 'image_group' =>  $this->saveImage($request->image),
            // 'background_image_group' => $this->saveImage($request->background_image_group),
        ];

        $user = auth()->user();
        $group = $user->groups()->create($data);
    
        return response()->json([
            'message' => 'Đã tạo nhóm thành công.',
            'group' => $group,
        ]);
    }

    public function editGroup(Request $request, $id){

        $request->validate([
            'group_name'=> 'required|string',
            'modifier' => 'in:1,2',
            'image_group' =>'required|image',
            'background_image_group' => 'required|image',
            'description' => 'required|string',
        ],[
            'group_name.required' =>'Tên nhóm không được bỏ trống.',
        ]);

        $group = Groups::find($id);
        // dd($group);
        if (!$group) {
            return response()->json([
                'message' => 'group not found',
            ], 404);
        }

        $user = auth()->user();
        // dd($user);
        $checkAuthor = $user->groups->where('id', $id)->first();
        // dd($checkAuthor);
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission to delete group',
            ], 403);
        }
        // dd($request);
        // dd($file);
        DB::beginTransaction();
        
        try {
            $group->group_name =  $request->group_name;
            $group->modifier =  $request->modifier;
            $group->image_group = $this->saveImage($request->image_group); 
            $group->background_image_group =  $this->saveImageBG($request->background_image_group);
            $group->description =  $request->description;
            
            $group->save();
            DB::commit();
            return response()->json([
                'message' => 'Cập nhập thông tin nhóm thành công.',
                'group' => $group
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function deleteGroup(Request $request, $id){
        $group = Groups::find($id);
        // dd($group);
        if (!$group) {
            return response()->json([
                'message' => 'group not found',
            ], 404);
        }

        $user = auth()->user();
        // dd($user);
        $checkAuthor = $user->groups()->where('id', $id)->first();
        // dd($checkAuthor);
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission to delete group',
            ], 403);
        }

        DB::beginTransaction();
        
        try {
            $group->delete();
            DB::commit();
            return response()->json([
                'message' => 'Đã xóa nhóm thành công.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function member($id)
    {
            $groups = Groups::find($id);
            if (!$groups) {
                return response()->json([
                    'message' => 'Group not found!'
                ], 500);
            }
    
            $members = User::whereHas('members', function($q) use($id) {
                $q->where('group_id', $id);
                // $q->where('friend_id', $friend_id);
            })->get();
    
            return response()->json([
                'userRequest' => $members,
            ]);
        // }
    }

    public function join(Request $request, $group_id)
    {
        $checkAuthor = Auth::user()->id;
        // dd($checkAuthor);
        // dd($group_id);
        $user_groups = DB::table('user_groups')
        ->where('group_id',$group_id)
        ->where('user_id',$checkAuthor)
        ->where('stt','=','1')
        ->first();
        // dd($user_groups);
        if($user_groups){
            return response()->json([
                'message' => 'Bạn đã là thành viên của nhóm.',
            ],404);
        }

        $groups = DB::table('groups')->where('id',$group_id)->first();
        // dd($groups);
            if (!$groups) {
                return response()->json([
                    'message' => 'Error',
                ], 404);
            }
        $group = DB::table('groups')->where('id',$group_id)->get('modifier');
        // dd((string)$group);
        // $groups = GroupUser::find($id);
        DB::beginTransaction();
        try {
            if($groups->modifier=='1')
            {
                DB::table('user_groups')->insert([
                    'user_id' => $checkAuthor,
                    'group_id'=>$group_id,
                    'stt'=>'1',
                ]);
                
                DB::commit();
                return response()->json([
                    'message' => 'Giờ bạn đã là thành viên của nhóm.',
                ]);
            }else {
                DB::table('user_groups')->insert([
                    'user_id' => $checkAuthor,
                    'group_id'=>$group_id,
                    'stt'=>'2',
                ]);
                
                DB::commit();
                return response()->json([
                    'message' => 'Đã gửi yêu cầu tham gia nhóm.',
                ]);
            }
            
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function acpMember(Request $request, $group_id)
    {
        
        DB::beginTransaction();
        try {
            // dd($request->u);
            $user_groups = GroupUser::where('group_id', '=', $group_id)
                ->where('user_id', '=',  $request->user_id)
                ->where('stt','=', '2')
            ->first();
            // dd($user_groups);
            $user_groups->stt = '1';
            $user_groups->save();
            DB::commit();
            return response()->json([
                'message' => 'success',
                'user_groups' => $user_groups,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
          
    }

    public function refMember(Request $request, $group_id)
    {
        DB::beginTransaction();
        
        try {
            $user_groups = GroupUser::where('group_id', '=', $group_id)
            ->where('user_id', '=',  $request->user_id)
            ->where('stt','=', '2')
            ->delete();
            // dd($user_groups);
            DB::commit();
            return response()->json([
                'message' => 'success',
                'user_groups' => $user_groups,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        
        return response()->json([
            'message' =>'success',
           'user_groups' => $user_groups,
       ]);
    }

    public function delMember(Request $request, $group_id,$user_id)
    {   

        $checkAuthor = Groups::where('user_id', Auth::user()->id)
        ->where('id',$group_id)
        ->first();
        // dd($checkAuthor);
        // dd($user_id);
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission',
            ], 403);
        }
        $user =  GroupUser::where('user_id',  $user_id)
        ->where('group_id', '=', $group_id)
        ->where('stt', '1')
        ->first();
        DB::beginTransaction();
        
        try {
           
            $user->delete();
            // dd($user_groups);
            DB::commit();
            return response()->json([
                'message' => 'Delete member',
                // 'user_groups' => $user_groups,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function memberRequests($group_id)
    {
        // dd($group_id);
        $memberRequests = User::whereHas('memberRequests', function($q) use ($group_id) {
            $q->where('group_id',$group_id);
        })->get();
        // dd($memberRequests);
        return response()->json([
            'memberRequest' => $memberRequests,
        ]);
    }

    public function outGroup(Request $request, $group_id)
    { 
        $checkAuthor = auth()->user()->id;

        // dd($user);
        DB::beginTransaction();    
        try {
            $user_groups = DB::table('user_groups')->where('group_id', '=', $group_id)
            ->where('user_id', '=',  $checkAuthor)
            ->where('stt','=', '1')
            ->delete();
            // dd($user_groups);
            DB::commit();
            return response()->json([
                'message' => 'Bạn đã rời nhóm.',
                'user_groups' => $user_groups,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
        
        return response()->json([
            'message' =>'success',
           'user_groups' => $user_groups,
       ]);
    }
}
