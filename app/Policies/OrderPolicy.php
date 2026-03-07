<?php

namespace App\Policies;

use App\Models\Order;
use App\Models\User;

class OrderPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, Order $order): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Order $order): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Order $order): bool
    {
        return $user->role === 'admin';
    }
}
