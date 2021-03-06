<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

use App\Jobs\SendEmail;
use App\Models\User;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        //check required
        if($request->missing('email') || $request->missing('password')){
            $errorMessage = 'Email and Password is Required';
            return response()->json(['message'=>$errorMessage], 400);
        }
        //check duplicate
        if(User::firstWhere('email', $request->email)){
            $errorMessage = 'Email already taken';
            return response()->json(['message'=>$errorMessage], 400);
        }

        //create user
        User::create([
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        //add scheduler message || using queue
        SendEmail::dispatch(['email' => $request->email]);

        $message = 'User successfully registered';
        return response()->json(['message'=>$message], 201);
    }

    public function login(Request $request)
    {
        $credentials = [
            'email'=>$request->email, 
            'password'=>$request->password
        ];
        //check user
        if(!Auth::attempt($credentials)){
            $message = 'Invalid credentials';
            return response()->json(['message'=>$message], 401);
        }

        //get user
        $user = Auth::user();
        
        //we can make it allow only 1 device
        //$user->tokens()->delete()
        
        //serve token
        $generatedToken = $request->user()->createToken($user->id);
        return response()->json(['access_token'=>$generatedToken->plainTextToken], 201);
    }
}
