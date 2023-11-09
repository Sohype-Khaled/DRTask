<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePasswordRequest;
use App\Models\Shop;

class ProfileController extends Controller
{
    public function updatePassword(UpdatePasswordRequest $request, Shop $shop)
    {
        $user = auth()->user();
        $user->password = bcrypt($request->input('new_password'));
        $user->password_changed_at = now();
        $user->password_change_required_at = now()->addDays(config('password.password_change_days'));
        $user->current_password_count = 0;
        $user->save();
        $user->addToPasswordHistory($user->password);

        return response()->json(['message' => 'Password updated successfully']);
    }

    public function profile()
    {
        return response()->json(['user' => auth()->user()]);
    }
}
