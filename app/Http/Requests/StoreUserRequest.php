<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class StoreUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->role === 'admin';
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'unique:users,email', 'max:255'],
            'phone_number' => ['nullable', 'string', 'max:50'],
            'password' => ['required', Password::defaults()],
            'role' => ['required', 'in:admin,staff,partner,driver,customer'],
        ];
    }
}
