<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class PasswordResetController extends Controller
{
    public function forgetPassword(Request $request){
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ]);
        $sent = Password::sendResetLink($request->only('email'));
        if($sent === Password::RESET_LINK_SENT){
            return response()->json('A verification link has been sent to your email.\ncheck your gmail', 200);
        }
        return response()->json('An error happend, please try again later.', 400);
    }

     public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => 'required',
            'email'                 => 'required|email|exists:users,email',
            'password'              => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required'
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                $user->tokens()->delete();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return response()->json([
                'message' => 'تم إعادة تعيين كلمة المرور بنجاح. يمكنك تسجيل الدخول الآن.'
            ], 200);
        }

        return response()->json([
            'message' => 'الرابط غير صالح أو منتهي الصلاحية'
        ], 400);
    }
}
