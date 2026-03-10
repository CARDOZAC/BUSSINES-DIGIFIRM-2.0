<?php

namespace App\Livewire\Admin;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Livewire\Component;
use Livewire\WithPagination;

class UsuarioIndex extends Component
{
    use WithPagination;

    public bool $mostrarModal = false;
    public ?int $usuarioId = null;

    public string $name = '';
    public string $email = '';
    public string $password = '';
    public ?int $empresa_id = null;
    public string $codigo_vendedor = '';
    public string $zona = '';
    public string $celular = '';
    public bool $active = true;

    protected $listeners = ['cerrarModal' => 'cerrarModal'];

    public function abrirCrear(): void
    {
        $this->reset(['usuarioId', 'name', 'email', 'password', 'empresa_id', 'codigo_vendedor', 'zona', 'celular', 'active']);
        $this->active = true;
        $this->mostrarModal = true;
    }

    public function abrirEditar(int $id): void
    {
        $usuario = User::findOrFail($id);
        $this->usuarioId = $id;
        $this->name = $usuario->name;
        $this->email = $usuario->email;
        $this->password = '';
        $this->empresa_id = $usuario->empresa_id;
        $this->codigo_vendedor = $usuario->codigo_vendedor ?? '';
        $this->zona = $usuario->zona ?? '';
        $this->celular = $usuario->celular ?? '';
        $this->active = $usuario->active;
        $this->mostrarModal = true;
    }

    public function cerrarModal(): void
    {
        $this->mostrarModal = false;
        $this->resetValidation();
    }

    public function guardar(): void
    {
        $reglas = [
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . ($this->usuarioId ?? 'NULL'),
            'empresa_id' => 'required|exists:empresas,id',
            'codigo_vendedor' => 'nullable|string|max:20|unique:users,codigo_vendedor,' . ($this->usuarioId ?? 'NULL'),
            'zona' => 'nullable|string|max:100',
            'celular' => 'nullable|string|max:20',
            'active' => 'boolean',
        ];

        if ($this->usuarioId) {
            if ($this->password) {
                $reglas['password'] = ['nullable', Password::defaults()];
            }
        } else {
            $reglas['password'] = ['required', Password::defaults()];
        }

        $this->validate($reglas, [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo es obligatorio.',
            'email.unique' => 'Este correo ya está registrado.',
            'empresa_id.required' => 'Seleccione la empresa.',
            'password.required' => 'La contraseña es obligatoria.',
        ]);

        if ($this->usuarioId) {
            $usuario = User::findOrFail($this->usuarioId);
            $usuario->update([
                'name' => strip_tags(trim($this->name)),
                'email' => strip_tags(trim($this->email)),
                'empresa_id' => $this->empresa_id,
                'codigo_vendedor' => $this->codigo_vendedor ? strip_tags(trim($this->codigo_vendedor)) : null,
                'zona' => $this->zona ? strip_tags(trim($this->zona)) : null,
                'celular' => $this->celular ? strip_tags(trim($this->celular)) : null,
                'active' => $this->active,
            ] + ($this->password ? ['password' => Hash::make($this->password)] : []));
            session()->flash('message', 'Usuario actualizado correctamente.');
        } else {
            $usuario = User::create([
                'name' => strip_tags(trim($this->name)),
                'email' => strip_tags(trim($this->email)),
                'password' => Hash::make($this->password),
                'empresa_id' => $this->empresa_id,
                'codigo_vendedor' => $this->codigo_vendedor ? strip_tags(trim($this->codigo_vendedor)) : null,
                'zona' => $this->zona ? strip_tags(trim($this->zona)) : null,
                'celular' => $this->celular ? strip_tags(trim($this->celular)) : null,
                'active' => $this->active,
            ]);
            $usuario->assignRole('vendedor');
            session()->flash('message', 'Vendedor creado correctamente.');
        }

        $this->cerrarModal();
    }

    public function toggleActivo(int $id): void
    {
        $usuario = User::findOrFail($id);
        if ($usuario->hasRole('super_admin')) {
            session()->flash('error', 'No se puede desactivar al super administrador.');
            return;
        }
        $usuario->update(['active' => !$usuario->active]);
        session()->flash('message', $usuario->active ? 'Usuario activado.' : 'Usuario desactivado.');
    }

    public function render()
    {
        return view('livewire.admin.usuario-index', [
            'usuarios' => User::with('empresa')->orderBy('name')->paginate(15),
            'empresas' => Empresa::activas()->orderBy('nombre')->get(),
        ])->layout('layouts.app');
    }
}
