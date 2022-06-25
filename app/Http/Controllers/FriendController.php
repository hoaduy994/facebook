<?php

namespace App\Http\Controllers;

use App\Models\Relation;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class friendController extends Controller
{
    public function friendRequests()
    {
        $usersRequests = User::whereHas('friendRequests', function($q) {
            $q->where('user_id', auth()->user()->id);
        })->get();
        // dd(auth()->user()->id);
        return response()->json([
            'userRequest' => $usersRequests,
        ]);
    }

    public function  friendList($id) {
        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found!'
            ], 500);
        }

        $friends = User::with('friends')->where('id', $id)
            // $q->where('friend_id', $friend_id);
        ->get();
        dd($friends);
        return response()->json([
            'user' => $friends,
        ]);
    }

    public function approveRequest(Request $request,$friend_id)
    {

          // send user_id: id accept, friend_id : id list 
        $relation = Relation::where('user_id', auth()->user()->id)
        ->where('friend_id', $friend_id)->first();
        // dd($relation);
        if (!$relation) {
            return response()->json([
                'message' => 'Friend_request not found'
            ], 500);
        }
        $updateRequest = [];
        if ($request->is_friend == Relation::ACCPET_FRIEND) {
            $updateRequest['is_friend'] = Relation::IS_FRIEND;
            
            // $relation->is_friend = Relation::IS_FRIEND;
        }
        else {
            $updateRequest['is_friend'] = Relation::NO_FRIEND;
            // $relation->is_friend = Relation::NO_FRIEND;
            DB::table('relations')->where('friend_id',$friend_id)->delete();
        }
        // dd($updateRequest);
        $relation->update($updateRequest);
        // $relation->save();
        return response()->json([
            // 'message' => 'Đã là bạn bè.',
            'userRequest' => $relation,
        ]);
    }

    public function sendRequest(Request $request, $user_id){
        $checkAuthor= auth()->user()->id;
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission',
            ], 403);
        }

        $friends = User::whereHas('friends', function($q) use ($checkAuthor,$user_id) {
            $q->where('user_id', $checkAuthor);
            $q->where('friend_id', $user_id);
            $q->where('is_friend','=','1');
        })->first();
        // dd($friends);
        if($friends){
            return response()->json([
                'message' => 'Đã là bạn bè.',
            ]);
        }

        DB::beginTransaction();
        try {
                DB::table('relations')->insert([
                    'user_id' => $checkAuthor,
                    'friend_id'=>$user_id,
                    'is_friend'=>'2',
                ]);
                
                DB::commit();
            
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }

        return response()->json([

            'message' => 'Đã gửi lời mời kết bạn.',

        ]);
    }

    
    public function delFriend(Request $request, $friend_id)
    {   

        $checkAuthor = Relation::where('user_id', Auth::user()->id)

        ->first();
        // dd($checkAuthor);
        // dd($user_id);
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission',
            ], 403);
        }
        $user =  Relation::where('user_id',  Auth::user()->id)
        ->where('friend_id', '=', $friend_id)
        ->where('is_friend', '1')
        ->first();
        // dd($user);
        DB::beginTransaction();
        
        try {
           
            $user->delete();
            // dd($user_groups);
            DB::commit();
            return response()->json([
                'message' => 'Đã xóa bạn bè.',
                // 'user_groups' => $user_groups,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

}
