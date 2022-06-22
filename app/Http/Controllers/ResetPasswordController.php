<?php

namespace App\Http\Controllers;

use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use App\Notifications\ResetPasswordRequest;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ResetPasswordController extends Controller
{
    public function sendMail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ],[
            'email.required' => 'Email không được bỏ trống.',
            'email.exists' => 'Email không tồn tại.',
        ]);
        $user = User::where('email','=',($request->email))->first();
        if(!$user){
            return response()->json([
                'message' => 'Email not found'
            ]);
        }
        // dd($user);
        $passwordReset = PasswordReset::updateOrCreate([
            'email' => $user->email,
        ], [
            'token' => Str::random(6),
        ]);
        if ($passwordReset) {
            $user->notify(new ResetPasswordRequest($passwordReset->token));
        }
  
        return response()->json([
        'message' => 'We have e-mailed your password reset link!'
        ]);
    }

    public function reset(Request $request)
    {
        $request->validate([
            // 'email' => 'required|email|exists:users,email',
            'token' => 'required|max:6',
            'password' => 'required|min:6',
        ],[
           
            'password.required' => 'Mật khẩu không được bỏ trống.',
            'password.min'=> 'Mật khẩu lớn hơn 6 kí tự.',
            'token.required' => 'Mã xác nhận không được bỏ trống.',
            'token.max'=> 'Mã xác nhận tối đa 6 kí tự.',
        ]);
        // dd($request);
        $checktoken = DB::table('password_resets')
        ->where('email', $request->email)
        ->where('token', $request->token)
        ->first();
        // dd($checktoken);
        if(!$checktoken){
            return response()->json([
                'message' => 'Mã xác nhận hoặc email không đúng.',
            ]);
        }else {
            User::where('email', $request->email)->update([
                'password' => Hash::make($request->password)
            ]);  
            
            DB::table('password_resets')->where([
            'email' => $request->email,
        ])->delete();

        }

        return response()->json([
            'message' => 'Bạn đã thay đổi mật khẩu thành công.',
        ]);
    }
}
