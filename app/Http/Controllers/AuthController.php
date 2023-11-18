<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginUserRequest;
use App\Http\Requests\StoreUserRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Traits\HttpResponses;
use App\Models\User;

class AuthController extends Controller
{
    use HttpResponses;

    public function register(StoreUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function login(LoginUserRequest $request): JsonResponse
    {
        $validated = $request->validated();

        if (!auth()->attempt($validated)) {
            return $this->error('', 401, 'Credentials do not match');
        }

        $user = User::where('email', $validated['email'])->first();

        return $this->success([
            'user' => $user,
            'token' => $user->createToken('API Token of ' . $user->name)->plainTextToken
        ]);
    }

    public function logout(): JsonResponse
    {
        auth()->user()->currentAccessToken()->delete();

        return $this->success([
            'message' => 'You have successfully been logged out'
        ]);
    }
}
