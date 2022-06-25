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


class reactionController extends Controller
{
    
    public function reaction_like(Request $request, $id)
    {
        $post_id = $id;
        $user_id = auth()->user()->id;
        $check_reaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id)->exists();
        
        if($check_reaction)
        {

            $reaction_detail = PostDetail::find($post_id);

            $lreaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id);
            $lreaction -> delete();
            
            DB::beginTransaction();
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_like' => $request->is_like+1
            ];
            $reaction = Reaction::create(array_merge($data));

            try {

                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail = PostDetail::find($post_id);

                $reaction_detail->is_like =  $total_like_post;
                $reaction_detail->is_love =  $total_love_post;
                $reaction_detail->is_haha =  $total_haha_post;
                $reaction_detail->is_angry =  $total_angry_post;
                $reaction_detail->is_sad =  $total_sad_post;
                $reaction_detail->is_wow =  $total_wow_post;
                

                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả like thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
            
        } 
        else 
        {
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_like' => $request->is_like+1
            ];
            $reaction = Reaction::create(array_merge($data));

            $reaction_detail = PostDetail::find($post_id);

            DB::beginTransaction();
            $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');

            try {

                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail->is_like =  $total_like_post;
                $reaction_detail->save();
                DB::commit();

                $reaction_detail = PostDetail::find($post_id);
                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả like thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        }

    }
    public function reaction_love(Request $request, $id)
    {
        // $post_id = (int)$id;
        $post_id = $id;

        
        $user_id = auth()->user()->id;
        $check_reaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id)->exists();
        
        if($check_reaction)
        {
            $reaction_detail = PostDetail::find($post_id);

            $lreaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id);
            $lreaction -> delete();

            DB::beginTransaction();
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_love' => $request->is_love+1
            ];
            $reaction = Reaction::create(array_merge($data));


            try {

                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail = PostDetail::find($post_id);

                $reaction_detail->is_like =  $total_like_post;
                $reaction_detail->is_love =  $total_love_post;
                $reaction_detail->is_haha =  $total_haha_post;
                $reaction_detail->is_angry =  $total_angry_post;
                $reaction_detail->is_sad =  $total_sad_post;
                $reaction_detail->is_wow =  $total_wow_post;

                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả love thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
            // dd($lreaction);
            
        } 
        else 
        {
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_love' => $request->is_love+1
            ];
            $reaction = Reaction::create(array_merge($data));

            $reaction_detail = PostDetail::find($post_id);

            DB::beginTransaction();
            $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');

            try {
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');
                $reaction_detail->is_love =  $total_love_post;
                $reaction_detail->save();
                DB::commit();

                $reaction_detail = PostDetail::find($post_id);
                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả love thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        }

    }
    public function reaction_haha(Request $request, $id)
    {
        // $post_id = (int)$id;
        $post_id = $id;

        
        $user_id = auth()->user()->id;
        $check_reaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id)->exists();
        
        if($check_reaction)
        {

            $lreaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id);
            $lreaction -> delete();

            DB::beginTransaction();
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_haha' => $request->is_haha+1
            ];
            $reaction = Reaction::create(array_merge($data));

            try {

                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail = PostDetail::find($post_id);

                $reaction_detail->is_like =  $total_like_post;
                $reaction_detail->is_love =  $total_love_post;
                $reaction_detail->is_haha =  $total_haha_post;
                $reaction_detail->is_angry =  $total_angry_post;
                $reaction_detail->is_sad =  $total_sad_post;
                $reaction_detail->is_wow =  $total_wow_post;

                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả haha thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
            // dd($lreaction);
            
        } 
        else 
        {
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_haha' => $request->is_haha+1
            ];
            $reaction = Reaction::create(array_merge($data));

            $reaction_detail = PostDetail::find($post_id);

            DB::beginTransaction();


            try {
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail->is_haha =  $total_haha_post;

                $reaction_detail->save();
                DB::commit();

                $reaction_detail = PostDetail::find($post_id);
                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả haha thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        }

    }
    public function reaction_angry(Request $request, $id)
    {
        $post_id = $id;     
        $user_id = auth()->user()->id;
        $check_reaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id)->exists();
        
        if($check_reaction)
        {
            $reaction_detail = PostDetail::find($post_id);

            $lreaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id);
            $lreaction -> delete();

            DB::beginTransaction();
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_angry' => $request->is_angry+1
            ];
            $reaction = Reaction::create(array_merge($data));

            try {

                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail = PostDetail::find($post_id);

                $reaction_detail->is_like =  $total_like_post;
                $reaction_detail->is_love =  $total_love_post;
                $reaction_detail->is_haha =  $total_haha_post;
                $reaction_detail->is_angry =  $total_angry_post;
                $reaction_detail->is_sad =  $total_sad_post;
                $reaction_detail->is_wow =  $total_wow_post;

                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả angry thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
            // dd($lreaction);
            
        } 
        else 
        {
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_angry' => $request->is_angry+1
            ];
            $reaction = Reaction::create(array_merge($data));

            $reaction_detail = PostDetail::find($post_id);

            DB::beginTransaction();


            try {
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail->is_angry =  $total_angry_post;
                
                $reaction_detail->save();
                DB::commit();

                $reaction_detail = PostDetail::find($post_id);
                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả angry thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }

        }

    }
    public function reaction_sad(Request $request, $id)
    {
        // $post_id = (int)$id;
        $post_id = $id;

        
        $user_id = auth()->user()->id;
        $check_reaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id)->exists();
        
        if($check_reaction)
        {
            $reaction_detail = PostDetail::find($post_id);

            $lreaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id);
            $lreaction -> delete();

            DB::beginTransaction();
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_sad' => $request->is_sad+1
            ];
            $reaction = Reaction::create(array_merge($data));

            try {

                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail = PostDetail::find($post_id);

                $reaction_detail->is_like =  $total_like_post;
                $reaction_detail->is_love =  $total_love_post;
                $reaction_detail->is_haha =  $total_haha_post;
                $reaction_detail->is_angry =  $total_angry_post;
                $reaction_detail->is_sad =  $total_sad_post;
                $reaction_detail->is_wow =  $total_wow_post;

                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả sad thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } 
        else 
        {
            $data = [
                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_sad' => $request->is_sad+1
            ];
            $reaction = Reaction::create(array_merge($data));

            $reaction_detail = PostDetail::find($post_id);

            DB::beginTransaction();

            try {
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail->is_sad =  $total_sad_post;
                
                $reaction_detail->save();
                DB::commit();

                $reaction_detail = PostDetail::find($post_id);
                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả sad thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }
    }
    public function reaction_wow(Request $request, $id)
    {
        $post_id = $id;
        $user_id = auth()->user()->id;
        $check_reaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id)->exists();
        
        if($check_reaction)
        {
            $reaction_detail = PostDetail::find($post_id);

            $lreaction = DB::table('user_posts')->where('post_id',$post_id)->where('user_id',$user_id);
            $lreaction -> delete();

            DB::beginTransaction();
            $data = [

                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_wow' => $request->is_wow+1
            ];
            $reaction = Reaction::create(array_merge($data));

            try {

                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail = PostDetail::find($post_id);

                $reaction_detail->is_like =  $total_like_post;
                $reaction_detail->is_love =  $total_love_post;
                $reaction_detail->is_haha =  $total_haha_post;
                $reaction_detail->is_angry =  $total_angry_post;
                $reaction_detail->is_sad =  $total_sad_post;
                $reaction_detail->is_wow =  $total_wow_post;

                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả wow thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        } 
        else 
        {
            $data = [
                'post_id' => $post_id,
                'user_id' => $user_id,
                'is_wow' => $request->is_wow+1
            ];
            $reaction = Reaction::create(array_merge($data));

            $reaction_detail = PostDetail::find($post_id);

            DB::beginTransaction();

            try {
                $total_love_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_love');
                $total_like_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_like');
                $total_haha_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_haha');
                $total_angry_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_angry');
                $total_sad_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_sad');
                $total_wow_post = DB::table('user_posts')->where('post_id',$post_id)->sum('is_wow');

                $reaction_detail->is_wow =  $total_wow_post;
                
                $reaction_detail->save();
                DB::commit();

                $reaction_detail = PostDetail::find($post_id);
                $total_reaction_post = $total_like_post + $total_love_post + $total_haha_post + $total_angry_post +$total_sad_post + $total_wow_post;
                $reaction_detail->total =  $total_reaction_post;
                $reaction_detail->save();

                DB::commit();

                return response()->json([
                    'message' => 'thả wow thành công',
                    'detail' => $reaction_detail
                ]);
            } catch (\Exception $e) {
                DB::rollBack();
                throw new \Exception($e->getMessage());
            }
        }
    }
}
