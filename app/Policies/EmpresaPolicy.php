<?php

namespace App\Policies;

use App\Models\Empresa;
use App\Models\User;

class EmpresaPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('admin-cartera');
    }

    public function view(User $user, Empresa $empresa): bool
    {
        return $user->empresa_id === $empresa->id;
    }

    public function create(User $user): bool
    {
        return false;
    }

    public function update(User $user, Empresa $empresa): bool
    {
        return $user->hasRole('admin-cartera')
            && $user->empresa_id === $empresa->id;
    }

    public function delete(User $user, Empresa $empresa): bool
    {
        return false;
    }
}
