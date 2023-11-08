<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserRequest;
use App\Models\Shop;
use App\Models\User;
use App\Traits\HTTPResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HTTPResponses;

    public function login(Request $request)
    {

    }

    public function register(SaveUserRequest $request, Shop $shop)
    {
        $user = User::where('email', $request->input('email'));

        if ($user->exists()) {
            $user = $user->first();
            if (!$shop->members()->where('user_id', $user->id)->exists())
                $shop->members()->attach($user->id);
            else
                return $this->error('User already exists', 400);

        } else {
            $request->validated($request->all());

            $user = User::create($request->all());

            $shop->members()->attach($user->id);

        }
        return $this->success([
            'user' => $user,
            'token' => $user->createToken("{$shop->slug}-{$user->email}-token")->plainTextToken
        ], 'User created successfully', 201);
    }

    public function logout(Request $request)
    {
    }
}
