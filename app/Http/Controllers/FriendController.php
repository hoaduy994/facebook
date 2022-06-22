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
        
        return response()->json([
            'userRequest' => $usersRequests,
        ]);
    }

    public function  friendList($user_id) {
        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'message' => 'User not found!'
            ], 500);
        }

        $friends = User::whereHas('friends', function($q) use($user_id) {
            $q->where('user_id', $user_id);
            // $q->where('friend_id', $friend_id);
        })->get();

        return response()->json([
            'userRequest' => $friends,
        ]);
    }

    public function approveRequest(Request $request, $user_id)
    {

          // send user_id: id accept, friend_id : id list 
        $relation = Relation::where('user_id', $user_id)
        ->where('friend_id', $request->friend_id)->first();

        if (!$relation) {
            return response()->json([
                'message' => 'Friend_request not found'
            ], 500);
        }
        $updateRequest = [];
        if ($request->type == Relation::ACCPET_FRIEND) {
            $updateRequest['is_friend'] = Relation::IS_FRIEND;
            // $relation->is_friend = Relation::IS_FRIEND
        }
        else {
            $updateRequest['is_friend'] = Relation::NO_FRIEND;
            // $relation->is_friend = Relation::IS_FRIEND
        }

        $relation->update($updateRequest);

        return response()->json([
            'userRequest' => $relation,
        ]);
    }
}
