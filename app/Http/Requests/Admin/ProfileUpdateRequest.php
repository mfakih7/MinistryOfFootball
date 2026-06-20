<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class ProfileUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->check();
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($this->user()->id),
            ],
            'current_password' => ['required_with:new_password', 'nullable', 'current_password'],
            'new_password' => ['nullable', 'string', Password::min(8), 'same:confirm_new_password'],
            'confirm_new_password' => ['nullable', 'required_with:new_password', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'current_password.current_password' => 'The current password is incorrect.',
            'new_password.same' => 'The new password confirmation does not match.',
        ];
    }
}
