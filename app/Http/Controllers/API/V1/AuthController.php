<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\UserResource;
use App\Enums\StatusEnum;
use App\Enums\RoleEnum;

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
        $statusProhibit = [StatusEnum::APPROVED->value, StatusEnum::ONGOING->value, StatusEnum::DONE->value];
 
        if (! $user || ! Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        if (!in_array($user->status_id, $statusProhibit)) {
            throw ValidationException::withMessages([
                'status' => ['Wait for the admin to verify your account.'],
            ]);
        }

        $user->tokens()->delete();
     
        return response()->json([
            'message' => 'Successfully login',
            'user' => UserResource::make($user),
            'token' => $user->createToken('api_token')->plainTextToken
        ], 200);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();

        return response()->json([
            'message' => 'Successfully logout'
        ], 200);
    }
}
