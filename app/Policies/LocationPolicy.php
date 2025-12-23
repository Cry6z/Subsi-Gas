<?php

namespace App\Policies;

use App\Models\Location;
use App\Models\User;

class LocationPolicy
{
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }

        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'distributor'], true);
    }

    public function view(User $user, Location $location): bool
    {
        return true;
    }

    public function create(User $user): bool
    {
        return $user->role === 'distributor';
    }

    public function update(User $user, Location $location): bool
    {
        return $user->role === 'distributor' && (int) $location->distributor_id === (int) $user->id;
    }

    public function delete(User $user, Location $location): bool
    {
        return $user->role === 'distributor' && (int) $location->distributor_id === (int) $user->id;
    }
}
