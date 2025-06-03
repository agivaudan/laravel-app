<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginAuthRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Symfony\Component\HttpFoundation\Response;

class AuthController extends Controller
{
    
    /**
     * Method to log into the app
     * 
     * @param LoginAuthRequest $request quick validator of the format of the request
     */
    public function login(LoginAuthRequest $request)
    {
        $credentials = $request->validated();

        // Attempt to log in the user
        if (Auth::guard('web')->attempt($credentials)) {
            $user = User::where('email', $request->email)->first();
            $token = $user->createToken($user->email.'-AuthToken');
            
            // If user is found, return the bearer token to it
            return response()->json([['user' => $user, 'token' => $token->plainTextToken], Response::HTTP_OK]);
        }

        // Otherwise, warn that the log in attempt has failed
        return response()->json(['message' => 'Failed to log in'], Response::HTTP_BAD_REQUEST);
    }

    /**
     * Redirect the user here if they try to access the app whithout being logged in
     */
    public function login_redirect()
    {
        return response()->json(['message' => 'You need to log in first'], Response::HTTP_UNAUTHORIZED);
    }

    /**
     * Method to log out a previously logged in user
     */
    public function logout()
    {
        auth()->user()->tokens()->delete();

        return response()->json(['message' => 'You logged out successfully'], Response::HTTP_OK);
    }
}
