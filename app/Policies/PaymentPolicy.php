<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Payment;

class PaymentPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, Payment $payment): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Payment $payment): bool
    {
        return $user->role === 'admin';
    }
}
