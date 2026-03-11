<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePartnerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'legal_name' => ['nullable', 'string', 'max:255'],
            'slug' => ['required', 'string', 'unique:partners,slug', 'max:255'],
            'business_type' => ['nullable', 'string', 'max:100'],
            'tax_id' => ['nullable', 'string', 'max:100'],
            'store_address' => ['nullable', 'string', 'max:500'],
            'support_phone' => ['nullable', 'string', 'max:100'],
            'status' => ['required', 'in:pending,active,suspended,rejected'],
            'user_id' => ['nullable', 'exists:users,id'],
        ];
    }
}
