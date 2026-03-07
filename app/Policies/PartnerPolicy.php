<?php

namespace App\Policies;

use App\Models\Partner;
use App\Models\User;

class PartnerPolicy
{
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function view(User $user, Partner $partner): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'admin';
    }

    public function update(User $user, Partner $partner): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Partner $partner): bool
    {
        return $user->role === 'admin';
    }
}
