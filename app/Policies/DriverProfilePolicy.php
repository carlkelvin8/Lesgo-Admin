<?php

namespace App\Policies;

use App\Models\User;
use App\Models\DriverProfile;

class DriverProfilePolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, DriverProfile $driverProfile): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function update(User $user, DriverProfile $driverProfile): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, DriverProfile $driverProfile): bool
    {
        return $user->role === 'admin';
    }
}
