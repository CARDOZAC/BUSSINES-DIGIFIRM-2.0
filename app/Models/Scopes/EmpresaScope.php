<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class EmpresaScope implements Scope
{
    /**
     * Aplica filtro por empresa_id para usuarios vendedores.
     * admin-cartera y super_admin ven todos los clientes.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $user = auth()->user();

        if (!$user) {
            return;
        }

        if ($user->hasAnyRole(['admin-cartera', 'super_admin'])) {
            return;
        }

        if ($user->empresa_id) {
            $builder->where($model->getTable() . '.empresa_id', $user->empresa_id);
        }
    }
}
