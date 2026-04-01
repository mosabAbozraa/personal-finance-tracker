<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
/** @var \App\Models\User $user */
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        $validated = $request->validate();
        $validated['password'] = Hash::make($request->password);
        if($request->hasFile('avatar')){
            $path = $request->file('avatar')->stor('avatars','public');
            $validated['avatar']=$path;
        }
        $user = User::create($validated);
        return response()->json($user, 201);
    }

    public function login(LoginRequest $request){
        if(!Auth::attempt($request->only('email','password'))){
            return response()->json(['message' => 'Wrong email or password'],
            401);
        }
        $user = User::where('email', $request->email)->first();
        $token = $user->createToken('auth_token')->plainTextToken;

        return (new LoginResource($user))->additional(['token'=>$token]); // if I want to add arguments to the resource this buildIn func does the work
    }
}
