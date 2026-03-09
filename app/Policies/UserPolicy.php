<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin-cartera');
    }

    public function view(User $authUser, User $targetUser): bool
    {
        if ($authUser->hasRole('admin-cartera')) {
            return $authUser->empresa_id === $targetUser->empresa_id;
        }

        return $authUser->id === $targetUser->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('admin-cartera');
    }

    public function update(User $authUser, User $targetUser): bool
    {
        if ($authUser->hasRole('admin-cartera')) {
            return $authUser->empresa_id === $targetUser->empresa_id;
        }

        return $authUser->id === $targetUser->id;
    }

    public function delete(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('admin-cartera')
            && $authUser->empresa_id === $targetUser->empresa_id
            && $authUser->id !== $targetUser->id;
    }

    public function toggleActive(User $authUser, User $targetUser): bool
    {
        return $authUser->hasRole('admin-cartera')
            && $authUser->empresa_id === $targetUser->empresa_id
            && $authUser->id !== $targetUser->id;
    }
}
