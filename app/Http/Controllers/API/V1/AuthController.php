<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;


use App\Models\User;
class AuthController extends Controller
{
    
    public function register(RegisterRequest $request)
    {
        User::create($request->validated());

        return response()->json(['message' => 'Successfully registered'], 200);
    }

    public function login(LoginRequest $request)
    {
        $user = User::where('email', $request->email)->first();
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user->tokens()->delete();
     
        return response()->json([
            'message' => 'Successfully login',
            'token' => $user->createToken('api_token')->plainTextToken
        ], 200);
    }

    public function logout()
    {

    }
}
