<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\LoginRequest;

class LoginController extends Controller
{
    public function login(LoginRequest $request){
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            // authentication passed...
            $user = Auth::user();

            // storing user id in token
            $token = $user->createToken('MyApp', ['user_id' => $user->id])->plainTextToken;
            
            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ], 200);
        }

        // authentication failed
        return response()->json([
            'message' => 'Invalid credentials',
        ], 401);
    }
}
