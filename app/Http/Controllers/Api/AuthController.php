<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{

    public function register(Request $request)
    {
        try {

            $credentials = $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:' . User::class],
                'password' => ['required', 'string'],
            ]);

            $user = User::create($credentials);

            $token = $user->createToken('auth_token');

            return response()->json([
                'message' => 'User registered successfully',
                'token' => $token->plainTextToken
            ], 201);
        } catch (\Exception $e) {
            Log::error('Failed to register user: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to register user',
                'error' => 'An error occurred during registration'
            ], 500);
        }


    }
    public function login(Request $request)
    {
        try {
            $credentials = $request->validate([
                'email' => ['required', 'string', 'email', 'max:255'],
                'password' => ['required', 'string'],
            ]);

            if (Auth::attempt($credentials)) {

                $request->user()->tokens()->delete();

                $token = $request->user()->createToken('auth_token');

                return response()->json([
                    'message' => 'Login successful',
                    'token' => $token->plainTextToken
                ], 200);
            }

            Log::warning(`Failed login attempt for email: $request->email`);
            return response()->json([
                'message' => 'Invalid credentials',
                'error' => 'The provided email or password is incorrect'
            ], 401);
            
        } catch (\Exception $e) {

            Log::error('Failed to login user: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to login',
                'error' => 'An error occurred during login'
            ], 500);

        }

    }

    public function logout(Request $request)
    {
        try{
            $request->user()->currentAccessToken()->delete();
        return response()->json([
            'message' => 'Logged out succesfully'
        ]);
        }
        catch(\Exception $e){
            Log::error('Logout failed: '. $e->getMessage());
            return response()->json([
                'message' => 'Failed to logout'
            ],500);
        }
        
    }


}