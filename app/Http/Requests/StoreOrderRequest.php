<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return in_array($this->user()?->role, ['admin', 'staff']);
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:users,id'],
            'service_id' => ['required', 'exists:services,id'],
            'partner_id' => ['nullable', 'exists:partners,id'],
            'driver_id' => ['nullable', 'exists:driver_profiles,id'],
            'pickup_address_id' => ['nullable', 'exists:addresses,id'],
            'dropoff_address_id' => ['nullable', 'exists:addresses,id'],
            'status' => ['required', 'in:pending,accepted,picked_up,completed,cancelled'],
            'payment_method' => ['required', 'in:cash,gcash,maya,card'],
            'payment_status' => ['required', 'in:pending,paid,failed,refunded'],
            'estimated_fare' => ['nullable', 'numeric', 'min:0'],
            'actual_fare' => ['nullable', 'numeric', 'min:0'],
            'scheduled_at' => ['nullable', 'date'],
        ];
    }
}
