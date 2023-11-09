<?php

namespace App\Http\Requests;

use App\Rules\UniquePasswordHistory;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use function Laravel\Prompts\password;

class UpdatePasswordRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $passwordRules = config('password.strong_password_rules');
        $passwordRules[] = 'confirmed';
        return [
            'current_password' => [
                'required',
                'string',
                function ($attribute, $value, $fail) {
                    if (!Hash::check($value, auth()->user()->password)) {
                        return $fail('The current password is incorrect.');
                    }
                }],
            'new_password' => $passwordRules,
        ];
    }
}
