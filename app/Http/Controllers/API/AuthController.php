<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\SaveUserRequest;
use App\Models\User;
use App\Traits\HTTPResponses;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    use HTTPResponses;

    public function login(Request $request)
    {

    }

    public function register(SaveUserRequest $request)
    {
        $request->validated($request->all());
        $user = User::create($request->all());

        return $this->success([
            'user' => $user,
            'token' => $user->createToken("{shop:slug}-{user:name}-token")->plainTextToken
        ], 'User created successfully', 201);
    }

    public function logout(Request $request)
    {
    }
}
