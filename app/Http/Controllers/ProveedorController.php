<?php

namespace App\Http\Controllers;

use App\Models\Empresa;
use App\Models\Proveedor;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class ProveedorController extends Controller
{
    private function puedeVerProveedoresDeOtraEmpresa(): bool
    {
        $user = Auth::user();
        return $user->hasAnyRole(['super_admin', 'admin-cartera']);
    }

    private function queryProveedoresAutorizados(Request $request)
    {
        $query = Proveedor::with('empresa')
            ->when($request->empresa_id, fn ($q) => $q->porEmpresa($request->empresa_id))
            ->when($request->keyword, fn ($q) => $q->buscar($request->keyword))
            ->when($request->fecha_inicio, fn ($q) => $q->whereDate('created_at', '>=', $request->fecha_inicio))
            ->when($request->fecha_fin, fn ($q) => $q->whereDate('created_at', '<=', $request->fecha_fin));

        if (!$this->puedeVerProveedoresDeOtraEmpresa()) {
            $query->where('empresa_id', Auth::user()->empresa_id);
        }

        return $query;
    }

    public function index(Request $request): View
    {
        $proveedores = $this->queryProveedoresAutorizados($request)
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $empresas = $this->puedeVerProveedoresDeOtraEmpresa()
            ? Empresa::activas()->orderBy('nombre')->get()
            : collect();

        return view('proveedores.index', compact('proveedores', 'empresas'));
    }

    public function create(): View
    {
        $empresaId = $this->puedeVerProveedoresDeOtraEmpresa()
            ? null
            : Auth::user()->empresa_id;

        $empresas = Empresa::activas()->orderBy('nombre')->get();
        $proveedores = $empresaId
            ? Proveedor::where('empresa_id', $empresaId)->where('activo', true)->orderBy('nombre')->get()
            : collect();

        return view('proveedores.create', [
            'empresas' => $empresas,
            'proveedores' => $proveedores,
            'empresaIdFija' => $empresaId,
        ]);
    }

    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $empresaId = $request->empresa_id;
        if (!$this->puedeVerProveedoresDeOtraEmpresa()) {
            if ($empresaId && (int) $empresaId !== (int) Auth::user()->empresa_id) {
                abort(403);
            }
            $empresaId = Auth::user()->empresa_id;
        }

        $id = $request->route('id') ?? 0;
        $rules = [
            'nombre' => 'required|string|max:255',
            'nit_rut' => 'nullable|string|max:50|unique:proveedores,nit_rut,' . $id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'ciudad' => 'nullable|string|max:100',
            'empresa_id' => 'required|exists:empresas,id',
            'observaciones' => 'nullable|string|max:500',
        ];

        $validated = $request->validate($rules);

        if (!$this->puedeVerProveedoresDeOtraEmpresa()) {
            $validated['empresa_id'] = Auth::user()->empresa_id;
        }

        $proveedor = Proveedor::create($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Proveedor creado correctamente.',
                'proveedor' => $proveedor->load('empresa'),
            ]);
        }

        return redirect()->route('proveedores.index')
            ->with('message', 'Proveedor creado correctamente.');
    }

    public function edit(int $id): View|RedirectResponse
    {
        $proveedor = Proveedor::with('empresa')->findOrFail($id);

        if (!$this->puedeVerProveedoresDeOtraEmpresa() && $proveedor->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $empresas = Empresa::activas()->orderBy('nombre')->get();
        $proveedores = Proveedor::where('empresa_id', $proveedor->empresa_id)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get();

        return view('proveedores.edit', [
            'proveedor' => $proveedor,
            'empresas' => $empresas,
            'proveedores' => $proveedores,
        ]);
    }

    public function update(Request $request, int $id): RedirectResponse|JsonResponse
    {
        $proveedor = Proveedor::findOrFail($id);

        if (!$this->puedeVerProveedoresDeOtraEmpresa() && $proveedor->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        if (!$this->puedeVerProveedoresDeOtraEmpresa() && $request->empresa_id && (int) $request->empresa_id !== (int) Auth::user()->empresa_id) {
            abort(403);
        }

        $rules = [
            'nombre' => 'required|string|max:255',
            'nit_rut' => 'nullable|string|max:50|unique:proveedores,nit_rut,' . $id,
            'telefono' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:100',
            'ciudad' => 'nullable|string|max:100',
            'empresa_id' => 'required|exists:empresas,id',
            'observaciones' => 'nullable|string|max:500',
            'activo' => 'nullable|boolean',
        ];

        $validated = $request->validate($rules);

        if (!$this->puedeVerProveedoresDeOtraEmpresa()) {
            $validated['empresa_id'] = Auth::user()->empresa_id;
        }

        $proveedor->update($validated);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Proveedor actualizado correctamente.',
                'proveedor' => $proveedor->fresh()->load('empresa'),
            ]);
        }

        return redirect()->route('proveedores.index')
            ->with('message', 'Proveedor actualizado correctamente.');
    }

    public function destroy(int $id): RedirectResponse|JsonResponse
    {
        $proveedor = Proveedor::findOrFail($id);

        if (!$this->puedeVerProveedoresDeOtraEmpresa() && $proveedor->empresa_id !== Auth::user()->empresa_id) {
            abort(403);
        }

        $proveedor->delete();

        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Proveedor eliminado correctamente.',
            ]);
        }

        return redirect()->route('proveedores.index')
            ->with('message', 'Proveedor eliminado correctamente.');
    }

    public function export(Request $request)
    {
        $proveedores = $this->queryProveedoresAutorizados($request)->get();

        $filename = 'proveedores_' . now()->format('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($proveedores) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['codigo_alterno', 'nombre', 'nit_rut', 'telefono', 'email', 'ciudad', 'empresa', 'created_at'], ';');

            foreach ($proveedores as $p) {
                fputcsv($file, [
                    $p->codigo_alterno,
                    $p->nombre,
                    $p->nit_rut ?? '',
                    $p->telefono ?? '',
                    $p->email ?? '',
                    $p->ciudad ?? '',
                    $p->empresa->nombre ?? '',
                    $p->created_at->format('Y-m-d H:i:s'),
                ], ';');
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function import(Request $request): JsonResponse
    {
        $request->validate([
            'archivo' => 'required|file|mimes:csv,txt|max:5120',
        ]);

        $empresaId = $request->empresa_id ?? Auth::user()->empresa_id;
        if (!$this->puedeVerProveedoresDeOtraEmpresa()) {
            $empresaId = Auth::user()->empresa_id;
        }

        $file = $request->file('archivo');
        $insertados = 0;
        $actualizados = 0;
        $errores = [];
        $fila = 0;

        $handle = fopen($file->getRealPath(), 'r');
        $encabezados = fgetcsv($handle, 0, ';') ?: fgetcsv($handle, 0, ',');

        if (!$encabezados) {
            fclose($handle);
            return response()->json([
                'insertados' => 0,
                'actualizados' => 0,
                'errores' => ['El archivo está vacío o no tiene formato válido.'],
            ]);
        }

        $encabezados = array_map('trim', $encabezados);
        $nombreIdx = array_search('nombre', array_map('strtolower', $encabezados));
        $empresaIdx = array_search('empresa_id', array_map('strtolower', $encabezados));

        if ($nombreIdx === false) {
            fclose($handle);
            return response()->json([
                'insertados' => 0,
                'actualizados' => 0,
                'errores' => ['Falta la columna obligatoria: nombre'],
            ]);
        }

        while (($row = fgetcsv($handle, 0, ';')) !== false) {
            if (count($row) === 1 && strpos($row[0], ',') !== false) {
                $row = str_getcsv($row[0], ',');
            }
            $fila++;

            $data = array_combine(array_slice($encabezados, 0, count($row)), $row);
            if (!$data) {
                continue;
            }

            $nombre = trim($data['nombre'] ?? $data['Nombre'] ?? '');
            if (empty($nombre)) {
                $errores[] = "Fila {$fila}: nombre vacío";
                continue;
            }

            $empId = $empresaIdx !== false ? (int) ($data[$encabezados[$empresaIdx]] ?? $empresaId) : $empresaId;
            if (!$this->puedeVerProveedoresDeOtraEmpresa()) {
                $empId = Auth::user()->empresa_id;
            }

            $nitRut = trim($data['nit_rut'] ?? $data['NIT'] ?? $data['nit'] ?? '');
            $telefono = trim($data['telefono'] ?? $data['Teléfono'] ?? '');
            $email = trim($data['email'] ?? $data['Email'] ?? '');
            $ciudad = trim($data['ciudad'] ?? $data['Ciudad'] ?? '');

            try {
                $existente = $nitRut
                    ? Proveedor::where('empresa_id', $empId)->where('nit_rut', $nitRut)->first()
                    : null;

                if ($existente) {
                    $existente->update([
                        'nombre' => $nombre,
                        'telefono' => $telefono ?: null,
                        'email' => $email ?: null,
                        'ciudad' => $ciudad ?: null,
                    ]);
                    $actualizados++;
                } else {
                    Proveedor::create([
                        'nombre' => $nombre,
                        'nit_rut' => $nitRut ?: null,
                        'telefono' => $telefono ?: null,
                        'email' => $email ?: null,
                        'ciudad' => $ciudad ?: null,
                        'empresa_id' => $empId,
                    ]);
                    $insertados++;
                }
            } catch (\Throwable $e) {
                $errores[] = "Fila {$fila}: " . $e->getMessage();
            }
        }
        fclose($handle);

        return response()->json([
            'insertados' => $insertados,
            'actualizados' => $actualizados,
            'errores' => $errores,
        ]);
    }

    public function plantillaCsv()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="plantilla_proveedores.csv"',
        ];

        $callback = function () {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['nombre', 'nit_rut', 'telefono', 'email', 'ciudad', 'empresa_id'], ';');
            fputcsv($file, ['Ejemplo Proveedor S.A.', '900.123.456-7', '3001234567', 'contacto@ejemplo.com', 'Bogotá', '1'], ';');
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function listaDropdown(Request $request): JsonResponse
    {
        $empresaId = $request->empresa_id ?? Auth::user()->empresa_id;

        if (!$this->puedeVerProveedoresDeOtraEmpresa() && Auth::user()->empresa_id) {
            $empresaId = Auth::user()->empresa_id;
        }

        $proveedores = Proveedor::where('empresa_id', $empresaId)
            ->where('activo', true)
            ->orderBy('nombre')
            ->get(['id', 'codigo_alterno', 'nombre']);

        return response()->json($proveedores);
    }
}
