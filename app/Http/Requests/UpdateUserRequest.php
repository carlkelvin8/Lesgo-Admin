<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Password;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        $user = $this->user();
        $targetUser = $this->route('user');
        
        // Admins can update anyone
        if ($user->role === 'admin') {
            return true;
        }
        
        // Users can update their own profile (but not their role)
        if ($user->id === $targetUser?->id && !$this->has('role')) {
            return true;
        }
        
        return false;
    }

    public function rules(): array
    {
        $userId = $this->route('user')?->id ?? $this->route('id');
        return [
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'email' => ['sometimes', 'nullable', 'email', 'unique:users,email,' . $userId, 'max:255'],
            'phone_number' => ['sometimes', 'nullable', 'string', 'max:50'],
            'password' => ['sometimes', 'nullable', Password::defaults()],
            'role' => ['sometimes', 'required', 'in:admin,staff,partner,driver,customer'],
        ];
    }
}
