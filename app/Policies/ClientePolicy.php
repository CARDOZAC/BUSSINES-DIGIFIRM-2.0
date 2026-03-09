<?php

namespace App\Policies;

use App\Models\Cliente;
use App\Models\User;

class ClientePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['admin-cartera', 'vendedor']);
    }

    public function view(User $user, Cliente $cliente): bool
    {
        if ($user->hasRole('admin-cartera')) {
            return $user->empresa_id === $cliente->empresa_id;
        }

        return $user->id === $cliente->vendedor_id;
    }

    public function create(User $user): bool
    {
        return $user->hasAnyRole(['admin-cartera', 'vendedor']) && $user->active;
    }

    public function update(User $user, Cliente $cliente): bool
    {
        if ($cliente->estaCompletado()) {
            return false;
        }

        if ($user->hasRole('admin-cartera')) {
            return $user->empresa_id === $cliente->empresa_id;
        }

        return $user->id === $cliente->vendedor_id;
    }

    public function delete(User $user, Cliente $cliente): bool
    {
        return $user->hasRole('admin-cartera')
            && $user->empresa_id === $cliente->empresa_id;
    }

    public function export(User $user): bool
    {
        return $user->hasRole('admin-cartera');
    }
}
