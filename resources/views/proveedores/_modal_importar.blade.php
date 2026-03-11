<dialog id="modal-importar" class="modal">
    <div class="modal-box max-w-md">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg">Importar Proveedores desde CSV</h3>
        <p class="text-sm text-base-content/60 py-2">
            El archivo debe contener las columnas: <strong>nombre</strong>, <strong>empresa_id</strong> (opcional para admin).
            Opcionales: nit_rut, telefono, email, ciudad.
        </p>

        <div class="mt-4">
            <a href="{{ route('proveedores.plantilla') }}" class="btn btn-sm btn-outline btn-primary gap-1 w-full sm:w-auto">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" /></svg>
                Descargar plantilla CSV
            </a>
        </div>

        <form id="form-importar" class="mt-4" enctype="multipart/form-data">
            @csrf
            @if(Auth::user()->hasAnyRole(['super_admin', 'admin-cartera']) && isset($empresas) && $empresas->isNotEmpty())
            <div class="form-control mb-3">
                <label class="label"><span class="label-text">Empresa destino</span></label>
                <select name="empresa_id" id="import-empresa-id" class="select select-bordered w-full">
                    <option value="">-- Seleccione (usa empresa del CSV si existe) --</option>
                    @foreach($empresas as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->nombre }}</option>
                    @endforeach
                </select>
            </div>
            @endif

            <div class="form-control">
                <label class="label"><span class="label-text">Archivo CSV <span class="text-error">*</span></span></label>
                <input type="file" name="archivo" id="import-archivo" accept=".csv" class="file-input file-input-bordered w-full" required>
            </div>

            <div id="import-progress" class="hidden mt-3">
                <progress class="progress progress-primary w-full"></progress>
                <p class="text-xs mt-1">Importando...</p>
            </div>

            <div id="import-resultado" class="hidden mt-3 p-3 rounded-lg bg-base-200 text-sm"></div>

            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('modal-importar').close()">Cerrar</button>
                <button type="submit" id="btn-importar" class="btn btn-primary">Importar</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop"><button>close</button></form>
</dialog>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('form-importar');
    const archivoInput = document.getElementById('import-archivo');
    const progress = document.getElementById('import-progress');
    const resultado = document.getElementById('import-resultado');
    const btnImportar = document.getElementById('btn-importar');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();
        if (!archivoInput.files.length) {
            alert('Seleccione un archivo CSV.');
            return;
        }

        btnImportar.disabled = true;
        progress.classList.remove('hidden');
        resultado.classList.add('hidden');

        const formData = new FormData(form);
        formData.append('archivo', archivoInput.files[0]);

        const empresaId = document.getElementById('import-empresa-id');
        if (empresaId && empresaId.value) {
            formData.set('empresa_id', empresaId.value);
        }

        try {
            const res = await fetch('{{ route("proveedores.import") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
                    'Accept': 'application/json',
                },
            });

            const data = await res.json();
            resultado.classList.remove('hidden');
            resultado.className = 'mt-3 p-3 rounded-lg text-sm ' + (data.errores?.length ? 'bg-warning/20' : 'bg-success/20');

            let msg = `${data.insertados ?? 0} insertados, ${data.actualizados ?? 0} actualizados`;
            if (data.errores && data.errores.length) {
                msg += `. Errores: ${data.errores.length}`;
                msg += '<ul class="list-disc list-inside mt-2 text-xs">';
                data.errores.slice(0, 5).forEach(err => { msg += `<li>${err}</li>`; });
                if (data.errores.length > 5) msg += `<li>... y ${data.errores.length - 5} más</li>`;
                msg += '</ul>';
            }
            resultado.innerHTML = msg;

            if (data.insertados > 0 || data.actualizados > 0) {
                setTimeout(() => window.location.reload(), 1500);
            }
        } catch (err) {
            resultado.classList.remove('hidden');
            resultado.className = 'mt-3 p-3 rounded-lg bg-error/20 text-sm';
            resultado.textContent = 'Error al importar: ' + err.message;
        } finally {
            btnImportar.disabled = false;
            progress.classList.add('hidden');
        }
    });
});
</script>
