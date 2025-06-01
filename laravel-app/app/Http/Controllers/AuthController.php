<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    
    /**
     * 
     */
    public function login(LoginAuthRequest $request)
    {
        $credentials = $request->validated();

        if (Auth::attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken($user->email.'-AuthToken');
            
            return response()->json([['user' => $user, 'token' => $token->plainTextToken], 200]);
        }

        return response()->json(['message' => 'Failed to log in'], 400);
    }

    /**
     * 
     */
    public function login_redirect()
    {
        return response()->json(['message' => 'You need to log in first'], 401);
    }

    /**
     * 
     */
    public function logout() {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'You logged out successfully'], 200);
    }
}
