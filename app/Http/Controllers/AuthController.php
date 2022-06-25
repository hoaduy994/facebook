<?php
namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    
    public function login(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:100',
            'password' => 'required|string|min:6',
        ],[
            'email.required' => 'Email không được bỏ trống.',
            'password.required' => 'Mật khẩu không được bỏ trống.',
            'password.min' => 'Mật khẩu lớn hơn 6 kí tự.'
        ]);

        $data = [
            'email' =>  $request->email,
            'password' => $request->password
        ];
        // dd($data);
        $remember_token = $request->has('remember_token') ? true : false; 

        if (Auth::attempt(['email' => $request->input('email'), 'password' => $request->input('password')], $remember_token)) {
            $user = auth()->user();

        }
        if (!$token = auth()->attempt($data)) {
            return response()->json(['error' => 'Tài khoản hoặc mật khẩu không đúng.'], 401);
        }

        return response()->json([
            'message' => 'Login successfully',
            'user' =>   auth()->user(),
            'token' => $this->createNewToken($token)->original
        ]);
    }
    /**
     * Register a User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function register (request $request) {
        $request->validate([
            'name' => 'required|string|between:2,100',
            'email' => 'required|string|email|max:100|unique:users,email,'.$request->email,
            'password' => 'required|string|min:6',
            // 'profile_photo_path' => 'required|image|'
        ],[
            'name.required' => 'Họ và tên không được bỏ trống.',
            'email.required' => 'Email không được bỏ trống.',
            'email.unique' => 'tài khoản đã tồn tại.',
            'password.required' => 'Mật khẩu không được bỏ trống.',
            'password.min'=> 'Mật khẩu lớn hơn 6 kí tự.'
        ]);
      
        $data = [
            'name' => $request->name,
            'email' =>  $request->email,
            'password' => $request->password,
            // 'profile_photo_path' => $this->saveImage($request->profile_photo_path),
        ];

        $user = User::create(array_merge(
                    $data,
                    ['password' => bcrypt($request->password)]
                ));
        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password
        ]);
        return response()->json([
            'message' => 'Đăng ký thành công.',
            'user' => $user,
            'token' => $this->createNewToken($token)->original
        ], 201);
    }
    public function me()
    {
        $user = Auth::user();

        return $this->responseHelper->successResponse(true, 'User', $user);
    }

    
    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout() {
        auth()->logout();
        return response()->json(['message' => 'User successfully signed out']);
    }
    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh() {
        // return $this->createNewToken(auth()->refresh());
    }
    /**
     * Get the authenticated User.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    // public function userProfile() {
    //     return response()->json(auth()->user());
    // }
    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => auth()->factory()->getTTL() * 60,
            // 'user' => auth()->user()
        ]);
    }

    public function editProfile(Request $request, $id){
        $request->validate([
            'name' => 'string',
            'email' => 'string|email|max:100',
            'profile_photo_path' => 'image',
            'background_img' => 'image',
            'bio' => 'string|max:255',
            'address' => 'string',
        ]);

        $users = User::find($id);
        $checkAuthor = auth()->user();
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission to update post',
            ], 403);
        }
        // dd($users);
        DB::beginTransaction();
        try {
            $users->name =  $request->name;
            $users->email =  $request->email;
            $users->profile_photo_path = $this->saveImage($request->profile_photo_path);
            $users->background_img = $this->saveImageBG($request->background_img);
            $users->bio = $request->bio;
            $users->address = $request->address;
            $users->save();
            DB::commit();
            return response()->json([
                'message' => 'Cập nhập bài viết thành công.',
                'user' => $users
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    }

    public function saveImage($image){
        $imageName =  uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/employees/avt', $imageName);
        return $imageName;
    }

    public function saveImageBG($image){
        $imageName =  uniqid() . '.' . $image->getClientOriginalExtension();
        $image->storeAs('public/employees/BGproile', $imageName);
        return $imageName;
    }

    public function userProfile($id)
    {
        $checkAuthor = auth()->user();
        if (!$checkAuthor) {
            return response()->json([
                'message' => 'Not permission',
            ], 403);
        }
        // dd($checkAuthor);
        $user=User::find($id);
        if(!$user){
            return response()->json([
                'message' => 'User not found',
            ],404);
        } 
        // dd($user);
        $posts = Post::with('postsCreated','user','comments','createPostdetail','reaction')->whereIn('user_id', $user)->get()->sortByDesc('created_at');
        // dd($posts);
        return response()->json([
            'message' => 'Get profile successfully',
            'user info' => $user,
            'posts' => $posts
        ]);
    }

    public function changePassword(Request $request){
       
        if (!(Hash::check($request->current_password, Auth::user()->password))) {
            // The passwords matches
            return response()->json([
            'message' => 'Your current password does not matches with the password you provided. Please try again.'
        ]);
        }

        if(strcmp($request->current_password, $request->confirm_password) == 0){
            //Current password and new password are same
            return response()->json([
                'message' =>'New Password cannot be same as your current password. Please choose a different password.'
        ]);
        }

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|',
            'confirm_password' => 'required|string|min:6|same:password',
        ]);

        DB::beginTransaction();
        try {
            $user = Auth::user();
            $user->password = bcrypt($request->confirm_password);
            $user->save();
            DB::commit();
            return response()->json([
                'message' => 'Password changed successfully !',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            throw new \Exception($e->getMessage());
        }
    
    }
}