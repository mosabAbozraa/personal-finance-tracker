<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EmailVerificationController extends Controller
{
    public function emailVerify(Request $request, $id, $hash){
        $user = User::findOrFail($id);
        if(!hash_equals(sha1($user->getEmailForVerification()),$hash)){
            return response()->json(['message' => 'Invalid verification link'], 403);
        }

        if($user->hasVerifiedEmail()){
            return response()->json(['message' => 'Email alreay verified'], 200);
        }

        $user->markEmailAsVerified();
        //event(new Verified($user));

        Log::info('User verified email', ['user_id' => $user->id]);
        return response()->json(['message' => 'Email verified successfully'], 200);
    }

    public function emailResend(Request $request){
        $user = $request->user();
        if($user->hasVerifiedEmail()){
            return response()->json(['message' => 'Email alreay verified'], 200);
        }
        $user->sendEmailVerificationNotification();

        Log::info('Verification email resent', ['user_id' => $user->id]);
        return response()->json(['message' => 'Verification email resent'], 200);
    }
}
