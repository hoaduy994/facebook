<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\models\User;


class UserController extends Controller
{
    public function editProfile(Request $request, $id){
        $request->validate([
            'name' => 'required|string',
            'email' => 'in:1,2,3',
            'profile_photo_path' => 'required|image',
            'background_img' => 'required|image',
        ]);

        $users = User::find($id);

        DB::beginTransaction();
        try {
            $users->name =  $request->name;
            $users->email =  $request->email;
            $users->profile_photo_path = $request->profile_photo_path;
            $users->background_img = $request->background_img;
            $users->save();
            DB::commit();
            return response()->json([
                'message' => 'Edit user profile successfully',
                'post' => $users
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }
    
    // public function upload(Request $request)
    // {
    //     if($request->hasFile('image')){
    //         $filename = $request->image->getClientOriginalName();
    //         $request->image->storeAs('images',$filename,'public');
    //         Auth()->user()->update(['image'=>$filename]);
    //     }
    //     return redirect()->back();
    // }
    // //
    // $users = User:: where ;

    // $posts = collect([]);

    // foreach ($users as $user) {
    //     $posts->push($user->posts);
    // }

    // $posts->orderByDesc('created_at');
    // getCountLikeAtributes() {
    //     $this->dsadasdahdk
    //     return 5;
    // }
    // Post {
    //     id : $this->id
    //     user_created : post->user,
    //     avartar: $this->avatar
    //     like_count: $this->count_like,
    //     count_comment: $this->count_ment
    // }

    // detail <post> {
        
    // }
    // list_comment: new CommentCollection($this->comments);

      //     // DB::beginTransaction();
    //     // try {
           
    //     //     $user_groups->stt = '1';
    //     //     $user_groups->save();
    //     //     DB::commit();
    //     //     return response()->json([
    //     //         'message' => 'success',
    //     //         'user_groups' => $user_groups,
    //     //     ]);
    //     // } catch (\Exception $e) {
    //     //     DB::rollBack();
    //     //     throw new \Exception($e->getMessage());
    //     // }
    // }

    // public function refMember(Request $request, $group_id)
    // {
    //     DB::beginTransaction();
    //     try {
    //         $user_groups = Groups::where([
    //             ['group_id', '=', $group_id],
    //             ['user_id', '=',  $request->user_id],
    //             ['stt'],'=', '2',
    //         ])->delete();

    //         DB::commit();
    //         return response()->json([
    //             'message' => 'success',
    //             'user_groups' => $user_groups,
    //         ]);
    //     } catch (\Exception $e) {
    //         DB::rollBack();
    //         throw new \Exception($e->getMessage());
    //     }

    //     return response()->json([
    //         'message' =>'success',
    //        'user_groups' => $user_groups,
    //    ]);
    // }

//     public function friendsOfMine()
// {
//     return $this->belongsToMany('App\User', 'friends', 'user_id', 'friend_id');
// }

// public function friendOf()
// {
//     return $this->belongsToMany('App\User', 'friends', 'friend_id', 'user_id');
// }

// public function friends()
// {
//     return $this->friendsOfMine()->wherePivot('accepted', true)->get()->merge($this->friendOf()->wherePivot('accepted', true)->get());
// }
}
