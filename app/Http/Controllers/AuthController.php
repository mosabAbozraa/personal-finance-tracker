<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\LoginResource;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(RegisterRequest $request){
        Log::info('Attemping User Registration',[
            'request_data' => $request->only('first_name','email')
        ]);
        try{
            $validated = $request->validated();
            $validated['password'] = Hash::make($request->password);
            if($request->hasFile('avatar')){
                $path = $request->file('avatar')->stor('avatars','public');
                $validated['avatar']=$path;
            }
            $user = User::create($validated);
            $user->assignRole('user');

            Log::info('User Registerd successfully',[
                'user_id'       => $user->id,
                'user_email'    => $user->email
            ]);
            return response()->json($user, 201);
        }
        catch(Exception $e){
            Log::error('User Registration Failed',[
                'request_data'  => $request->only('first_name','email'),
                'error'         => $e
            ]);
            return response()->json([
                'message' => 'User Registration Failed, try again',
                'error'   => $e
            ], 500);
        }

    }

    public function login(LoginRequest $request){
        Log::info("Attemping User Login",[
            'user_email' => $request->email
        ]);
        try{
            if(!Auth::attempt($request->only('email','password'))){
                Log::warning('Login Failed, Invalid Credentials',[
                    'request_email' => $request->email
                ]);
                return response()->json([
                    'message' => 'Wrong email or password'],
                401);
            }
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken('auth_token')->plainTextToken;

            Log::info('User Login successfully',[
                'user_id'   => $user->id,
                'user_email'=> $user->email
            ]);
            return (new LoginResource($user))->additional(['token'=>$token]); // if I want to add arguments to the resource this buildIn func does the work
        }
        catch(Exception $e){
            Log::error('Login Failed',[
                'user_email'   => $request->email,
                'error'        => $e
            ]);
            return response()->json([
                'message' => 'Login Failed, try again',
                'error'   => $e
            ], 500);
        }

    }
}
