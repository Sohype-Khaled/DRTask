<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\Shop;
use App\Models\User;
use App\Traits\HTTPResponses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    use HTTPResponses;

    public function login(LoginRequest $request, Shop $shop)
    {
        $request->validated($request, $request->rules());
        $user = User::where('email', $request->email)->first();

        if (!$user->exists() || !$shop->members->contains($user))
            return $this->error('Invalid credentials', 401);

        if ($user && Auth::attempt($request->only('email', 'password'))) {

            return $this->getTokenResponse($shop, $user, 'User logged in successfully', 200);
        } else {
            return $this->error('Invalid credentials', 401);
        }
    }

    public function getTokenResponse(Shop $shop, $user, $message = null, $status = 200)
    {
        return $this->success([
            'user' => $user,
            'token' => $user->createToken("{$shop->slug}-{$user->email}-token")->plainTextToken
        ], $message, 201);
    }

    public function register(Request $request, Shop $shop)
    {
        $user = User::where('email', $request->input('email'));

        if ($user->exists()) {
            $user = $user->first();
            if (!$shop->members->contains($user))
                $shop->members()->attach($user->id);
            else
                return $this->error('User already exists', 422);

        } else {
            $this->validate($request, [
                "name" => "required|string|max:255",
                "email" => "required|string|email|max:255|unique:users,email",
                "password" => "required|string|min:8|confirmed",
            ]);

            $user = User::create($request->all());

            $shop->members()->attach($user->id);
        }
        return $this->getTokenResponse($shop, $user, 'User created successfully', 201);
    }

    public function logout(Request $request)
    {
        Auth::user()->currentAccessToken()->delete();
        return $this->success([], 'User logged out successfully', 200);
    }
}
