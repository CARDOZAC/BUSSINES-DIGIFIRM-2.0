<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;

class ClientePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin-cartera', 'super_admin', 'vendedor']);
    }

    public function view(User $user, Cliente $cliente): bool
    {
        if ($user->hasAnyRole(['admin-cartera', 'super_admin'])) {
            return true;
        }

        return $user->id === $cliente->vendedor_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'admin-cartera', 'vendedor']) && $user->active;
    }

    public function update(User $user, Cliente $cliente): bool
    {
        if ($cliente->estaCompletado()) {
            return false;
        }

        if ($user->hasAnyRole(['admin-cartera', 'super_admin'])) {
            return true;
        }

        return $user->id === $cliente->vendedor_id;
    }

    public function delete(User $user, Cliente $cliente): bool
    {
        return $user->hasAnyRole(['admin-cartera', 'super_admin']);
    }

    public function export(User $user, mixed $model = null): bool
    {
        return $user->hasAnyRole(['admin-cartera', 'super_admin']);
    }
}
