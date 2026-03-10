<div>
    {{-- ========== HEADER FIJO ========== --}}
    <div class="bg-white border-b border-gray-200 shadow-sm sticky top-0 z-40">
        <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
            <div class="flex items-center gap-3">
                @if($empresaSeleccionada && $empresaSeleccionada->logo_path)
                    <img src="{{ asset($empresaSeleccionada->logo_path) }}"
                         alt="{{ $empresaSeleccionada->nombre }}"
                         class="h-10 w-10 rounded-full bg-gray-100 p-0.5 object-contain border border-gray-200">
                @else
                    <div class="h-10 w-10 rounded-full bg-gray-100 border border-gray-200 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                    </div>
                @endif
                <div>
                    <h1 class="text-lg font-bold leading-tight text-gray-900">
                        {{ $empresaSeleccionada ? $empresaSeleccionada->nombre : 'Sistema de Creación de Clientes' }}
                    </h1>
                    <p class="text-xs text-gray-600">Registro de Clientes</p>
                </div>
            </div>
            <div class="text-right">
                <p class="text-sm font-semibold text-gray-900">{{ Auth::user()->name }}</p>
                <p class="text-xs text-gray-600">Vendedor</p>
            </div>
        </div>
    </div>

    @if(!$guardadoExitoso)
    {{-- ========== BARRA DE PROGRESO ========== --}}
    <div class="max-w-4xl mx-auto px-4 pt-6 pb-2">
        <ul class="steps steps-horizontal w-full text-xs sm:text-sm">
            <li wire:click="irAPaso(1)" class="step {{ $paso >= 1 ? 'step-primary' : '' }} cursor-pointer">
                Empresa
            </li>
            <li wire:click="irAPaso(2)" class="step {{ $paso >= 2 ? 'step-primary' : '' }} cursor-pointer">
                Datos
            </li>
            <li wire:click="irAPaso(3)" class="step {{ $paso >= 3 ? 'step-primary' : '' }} cursor-pointer">
                Tributario
            </li>
            <li wire:click="irAPaso(4)" class="step {{ $paso >= 4 ? 'step-primary' : '' }} cursor-pointer">
                Firma
            </li>
        </ul>

        <div class="flex items-center justify-center gap-3 mt-3">
            <progress class="progress progress-primary w-48 sm:w-64" value="{{ $progresoPorcentaje }}" max="100"></progress>
            <span class="text-sm font-bold text-primary">{{ $progresoPorcentaje }}% completado</span>
        </div>
    </div>

    {{-- ========== CONTENIDO DE PASOS ========== --}}
    <div class="max-w-4xl mx-auto px-4 pb-10">

        {{-- Flash de error --}}
        @if(session()->has('error'))
            <div class="alert alert-error mb-4 shadow-md">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        {{-- ==================== PASO 1: EMPRESA Y SOLICITUD ==================== --}}
        @if($paso === 1)
        <div class="card bg-base-100 shadow-xl animate-fade-in">
            <div class="card-body">
                <h2 class="card-title text-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                    Empresa y Tipo de Solicitud
                </h2>
                <p class="text-sm text-base-content/60 mb-4">Seleccione la empresa y el tipo de solicitud para el registro del cliente.</p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    {{-- Empresa --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Empresa <span class="text-error">*</span>
                        </label>
                        <select wire:model.live="empresa_id" class="select w-full">
                            <option value="">Seleccione empresa...</option>
                            @foreach($empresas as $empresa)
                                <option value="{{ $empresa->id }}">{{ $empresa->nombre }} — NIT {{ $empresa->nit }}</option>
                            @endforeach
                        </select>
                        @error('empresa_id') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipo solicitud --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Tipo de Solicitud <span class="text-error">*</span>
                        </label>
                        <select wire:model="tipo_solicitud" class="select w-full">
                            <option value="">Seleccione tipo...</option>
                            <option value="creacion">Creación de cliente</option>
                            <option value="actualizacion">Actualización de datos</option>
                            <option value="reactivacion">Reactivación de cliente</option>
                        </select>
                        @error('tipo_solicitud') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Fecha de Diligenciamiento <span class="text-error">*</span>
                        </label>
                        <input type="date" wire:model="fecha_diligenciamiento" class="input w-full" />
                        @error('fecha_diligenciamiento') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Zona --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Zona</label>
                        <input type="text" wire:model="zona" class="input w-full" placeholder="Ej: Zona Norte, Centro..." />
                        @error('zona') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Info empresa seleccionada --}}
                @if($empresaSeleccionada)
                    <div class="mt-4 p-3 bg-primary/5 border border-primary/20 rounded-lg">
                        <p class="text-sm font-medium text-primary">{{ $empresaSeleccionada->razon_social }}</p>
                        <p class="text-xs text-base-content/60">
                            NIT: {{ $empresaSeleccionada->nit }}
                            @if($empresaSeleccionada->direccion) &bull; {{ $empresaSeleccionada->direccion }} @endif
                        </p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        {{-- ==================== PASO 2: INFORMACIÓN GENERAL ==================== --}}
        @if($paso === 2)
        <div class="card bg-base-100 shadow-xl animate-fade-in">
            <div class="card-body">
                <h2 class="card-title text-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                    </svg>
                    Información General del Cliente
                </h2>
                <p class="text-sm text-base-content/60 mb-4">Datos de identificación y contacto del cliente.</p>

                {{-- Sección: Identificación --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase">Identificación</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Tipo de Documento <span class="text-error">*</span>
                        </label>
                        <select wire:model="tipo_documento" class="select w-full">
                            <option value="">Seleccione...</option>
                            <option value="CC">Cédula de Ciudadanía</option>
                            <option value="NIT">NIT</option>
                            <option value="CE">Cédula de Extranjería</option>
                        </select>
                        @error('tipo_documento') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Número de Documento <span class="text-error">*</span>
                        </label>
                        <input type="text" wire:model="numero_documento" class="input w-full" placeholder="Ej: 1234567890" />
                        @error('numero_documento') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium mb-1">
                            Nombre / Razón Social <span class="text-error">*</span>
                        </label>
                        <input type="text" wire:model="nombre_razon_social" class="input w-full" placeholder="Nombre completo o razón social" />
                        @error('nombre_razon_social') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Sección: Contacto y Ubicación --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Contacto y Ubicación</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre del Establecimiento</label>
                        <input type="text" wire:model="nombre_establecimiento" class="input w-full" placeholder="Nombre comercial" />
                        @error('nombre_establecimiento') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Correo Electrónico</label>
                        <input type="email" wire:model="correo_electronico" class="input w-full" placeholder="correo@ejemplo.com" />
                        @error('correo_electronico') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Dirección <span class="text-error">*</span>
                        </label>
                        <input type="text" wire:model="direccion" class="input w-full" placeholder="Calle, carrera, número..." />
                        @error('direccion') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Barrio</label>
                        <input type="text" wire:model="barrio" class="input w-full" placeholder="Nombre del barrio" />
                        @error('barrio') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Ciudad / Departamento <span class="text-error">*</span>
                        </label>
                        <input type="text" wire:model="ciudad_departamento" class="input w-full" placeholder="Ej: Bogotá, Cundinamarca" />
                        @error('ciudad_departamento') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Celular <span class="text-error">*</span>
                        </label>
                        <input type="tel" wire:model="celular" class="input w-full" placeholder="3001234567" />
                        @error('celular') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Sección: Tipo de Negocio --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Tipo de Negocio</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Tipo de Negocio <span class="text-error">*</span>
                        </label>
                        <select wire:model.live="tipo_negocio" class="select w-full">
                            <option value="">Seleccione...</option>
                            <option value="mayorista">Mayorista</option>
                            <option value="supermercado">Supermercado</option>
                            <option value="tienda">Tienda</option>
                            <option value="otro">Otro</option>
                        </select>
                        @error('tipo_negocio') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if($tipo_negocio === 'otro')
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Especifique el tipo <span class="text-error">*</span>
                        </label>
                        <input type="text" wire:model="tipo_negocio_otro" class="input w-full" placeholder="Describa el tipo de negocio" />
                        @error('tipo_negocio_otro') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                    @endif
                </div>

                {{-- Sección: Representante Legal (condicional para NIT) --}}
                @if($tipo_documento === 'NIT')
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Representante Legal</div>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Nombre Representante</label>
                        <input type="text" wire:model="representante_legal_nombre" class="input w-full" placeholder="Nombre completo" />
                        @error('representante_legal_nombre') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Tipo Doc. Representante</label>
                        <select wire:model="representante_legal_tipo_documento" class="select w-full">
                            <option value="">Seleccione...</option>
                            <option value="CC">CC</option>
                            <option value="NIT">NIT</option>
                            <option value="CE">CE</option>
                        </select>
                        @error('representante_legal_tipo_documento') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Nro. Doc. Representante</label>
                        <input type="text" wire:model="representante_legal_numero_documento" class="input w-full" placeholder="Número de documento" />
                        @error('representante_legal_numero_documento') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- ==================== PASO 3: INFORMACIÓN TRIBUTARIA ==================== --}}
        @if($paso === 3)
        <div class="card bg-base-100 shadow-xl animate-fade-in">
            <div class="card-body">
                <h2 class="card-title text-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z" />
                    </svg>
                    Información Tributaria
                </h2>
                <p class="text-sm text-base-content/60 mb-4">Datos tributarios y fiscales del cliente.</p>

                {{-- Chiluto: Precargar datos persona natural sin RUT --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase">Persona Natural sin RUT</div>
                <div class="p-4 bg-info/10 border border-info/30 rounded-lg">
                    <p class="text-sm text-base-content/70 mb-3">
                        Si el cliente es <strong>persona natural</strong> que <strong>no tiene RUT</strong> ni registros tributarios en la DIAN,
                        puede precargar los datos generales con un solo clic.
                    </p>
                    <button type="button"
                            wire:click="precargarDatosPersonaNaturalSinRut"
                            class="btn btn-sm btn-outline btn-info gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12" />
                        </svg>
                        Precargar datos (Persona natural sin RUT)
                    </button>
                    @if($persona_natural_no_responsable_iva)
                        <span class="badge badge-success badge-sm ml-2 mt-2">Datos precargados</span>
                    @endif
                    @if(session('chiluto_ok'))
                        <div class="alert alert-success alert-sm mt-3">
                            <span>{{ session('chiluto_ok') }}</span>
                        </div>
                    @endif
                </div>

                {{-- CIIU y Actividad --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Actividad Económica</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Código CIIU</label>
                        <input type="text" wire:model="codigo_ciiu" class="input w-full" placeholder="Ej: 4711" maxlength="10" />
                        <p class="text-xs text-base-content/50 mt-1">Código de clasificación industrial</p>
                        @error('codigo_ciiu') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium mb-1">Actividad Económica</label>
                        <input type="text" wire:model="actividad_economica" class="input w-full" placeholder="Describa la actividad económica" />
                        @error('actividad_economica') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Responsabilidades Tributarias --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Responsabilidades Tributarias</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-5">
                    {{-- Responsable IVA --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">
                            Responsable de IVA <span class="text-error">*</span>
                        </label>
                        <select wire:model="responsable_iva" class="select w-full">
                            <option value="no_responsable">No Responsable de IVA</option>
                            <option value="responsable">Responsable de IVA</option>
                        </select>
                        @error('responsable_iva') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Tipo Régimen --}}
                    <div>
                        <label class="block text-sm font-medium mb-1">Tipo de Régimen</label>
                        <select wire:model="tipo_regimen" class="select w-full">
                            <option value="">Ninguno</option>
                            <option value="gran_contribuyente">Gran Contribuyente</option>
                            <option value="autorretenedor">Autorretenedor</option>
                        </select>
                        @error('tipo_regimen') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- Toggle: Agente Retención Fuente --}}
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <p class="text-sm font-medium">Agente de Retención en la Fuente</p>
                            <p class="text-xs text-base-content/50">¿El cliente es agente de retención?</p>
                        </div>
                        <input type="checkbox" wire:model="agente_retencion_fuente" class="toggle toggle-primary" />
                    </div>

                    {{-- Toggle: Agente Retención ICA --}}
                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <p class="text-sm font-medium">Agente de Retención de ICA</p>
                            <p class="text-xs text-base-content/50">¿El cliente es agente de retención de ICA?</p>
                        </div>
                        <input type="checkbox" wire:model="agente_retencion_ico" class="toggle toggle-primary" />
                    </div>
                </div>

                {{-- Facturación y SAGRILAFT --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Facturación Electrónica y SAGRILAFT</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Correo Factura Electrónica</label>
                        <input type="email" wire:model="correo_factura_electronica" class="input w-full" placeholder="facturacion@empresa.com" />
                        @error('correo_factura_electronica') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium mb-1">Fuente/Origen de Recursos</label>
                        <textarea wire:model="fuente_recursos" class="textarea w-full" rows="3" placeholder="Describa el origen de los recursos del cliente (SAGRILAFT)"></textarea>
                        @error('fuente_recursos') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- ==================== PASO 4: FIRMA, FOTO Y CONFIRMACIÓN ==================== --}}
        @if($paso === 4)
        <div class="card bg-base-100 shadow-xl animate-fade-in">
            <div class="card-body">
                <h2 class="card-title text-primary gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                    </svg>
                    Firma Digital y Confirmación
                </h2>
                <p class="text-sm text-base-content/60 mb-4">Capture la firma del cliente y confirme los datos.</p>

                {{-- ===== FIRMA DIGITAL (wire:ignore evita que Livewire re-renderice el canvas) ===== --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase">Firma del Cliente</div>
                <div wire:ignore
                     x-data="{
                        drawing: false,
                        hasFirma: false,
                        canvas: null,
                        ctx: null,

                        init() {
                            this.$nextTick(() => this.resizeCanvas());
                            window.addEventListener('resize', () => this.resizeCanvas());
                        },

                        resizeCanvas() {
                            this.canvas = this.$refs.firmaCanvas;
                            const container = this.$refs.canvasContainer;
                            if (!this.canvas || !container) return;
                            const w = container.offsetWidth;
                            const h = 200;
                            if (this.canvas.width !== w || this.canvas.height !== h) {
                                this.canvas.width = w;
                                this.canvas.height = h;
                                this.ctx = this.canvas.getContext('2d');
                                this.ctx.strokeStyle = '#1e3a5f';
                                this.ctx.lineWidth = 2.5;
                                this.ctx.lineCap = 'round';
                                this.ctx.lineJoin = 'round';
                            }
                        },

                        getPos(e) {
                            const rect = this.canvas.getBoundingClientRect();
                            const scaleX = this.canvas.width / rect.width;
                            const scaleY = this.canvas.height / rect.height;
                            const touch = e.touches ? e.touches[0] : e;
                            return {
                                x: (touch.clientX - rect.left) * scaleX,
                                y: (touch.clientY - rect.top) * scaleY
                            };
                        },

                        startDraw(e) {
                            if (e.cancelable) e.preventDefault();
                            if (!this.ctx) return;
                            this.drawing = true;
                            const pos = this.getPos(e);
                            this.ctx.beginPath();
                            this.ctx.moveTo(pos.x, pos.y);
                        },

                        draw(e) {
                            if (!this.drawing || !this.ctx) return;
                            if (e.cancelable) e.preventDefault();
                            const pos = this.getPos(e);
                            this.ctx.lineTo(pos.x, pos.y);
                            this.ctx.stroke();
                            this.ctx.beginPath();
                            this.ctx.moveTo(pos.x, pos.y);
                        },

                        endDraw() {
                            if (!this.drawing) return;
                            this.drawing = false;
                            this.hasFirma = true;
                            $wire.set('firma_base64', this.canvas.toDataURL('image/png'));
                        },

                        clear() {
                            if (!this.ctx) return;
                            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);
                            this.hasFirma = false;
                            $wire.set('firma_base64', '');
                        }
                     }"
                     class="space-y-2">
                    <div x-ref="canvasContainer" class="relative w-full">
                        <canvas x-ref="firmaCanvas"
                                x-on:mousedown="startDraw($event)"
                                x-on:mousemove="draw($event)"
                                x-on:mouseup="endDraw()"
                                x-on:mouseleave="endDraw()"
                                x-on:touchstart="startDraw($event)"
                                x-on:touchmove.prevent="draw($event)"
                                x-on:touchend="endDraw()"
                                x-on:touchcancel="endDraw()"
                                class="border-2 border-dashed rounded-xl cursor-crosshair bg-white w-full block"
                                style="touch-action: none; height: 200px;"
                                x-bind:class="hasFirma ? 'border-success' : 'border-base-300'">
                        </canvas>
                        <div x-show="!hasFirma"
                             x-transition:leave.opacity
                             class="absolute inset-0 flex items-center justify-center pointer-events-none">
                            <span class="text-gray-300 text-lg font-light select-none">Firme aquí</span>
                        </div>
                    </div>
                    <div class="flex items-center gap-3">
                        <button x-on:click="clear()" type="button" class="btn btn-outline btn-sm gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                            </svg>
                            Limpiar firma
                        </button>
                        <span x-show="hasFirma" x-transition class="badge badge-success badge-sm gap-1">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Firma capturada
                        </span>
                    </div>
                </div>
                @error('firma_base64') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror

                {{-- ===== DOCUMENTOS PDF (Cédula y RUT) - Opcionales ===== --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Documentos PDF <span class="badge badge-xs badge-ghost">opcionales</span></div>
                <div class="space-y-4">
                    <p class="text-xs text-base-content/50">Suba los documentos en formato PDF. Se almacenan en la nube para consulta.</p>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium mb-1">Cédula de Ciudadanía (PDF)</label>
                            <input type="file"
                                   wire:model="documento_cedula_pdf"
                                   accept="application/pdf"
                                   class="file-input file-input-bordered w-full" />
                            <div wire:loading wire:target="documento_cedula_pdf" class="flex items-center gap-2 mt-2 text-sm text-primary">
                                <span class="loading loading-spinner loading-xs"></span>
                                Cargando...
                            </div>
                            @if($documento_cedula_pdf)
                                <p class="text-xs text-success mt-1">✓ Archivo cargado</p>
                            @endif
                            @error('documento_cedula_pdf') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium mb-1">RUT (PDF)</label>
                            <input type="file"
                                   wire:model="documento_rut_pdf"
                                   accept="application/pdf"
                                   class="file-input file-input-bordered w-full" />
                            <div wire:loading wire:target="documento_rut_pdf" class="flex items-center gap-2 mt-2 text-sm text-primary">
                                <span class="loading loading-spinner loading-xs"></span>
                                Cargando...
                            </div>
                            @if($documento_rut_pdf)
                                <p class="text-xs text-success mt-1">✓ Archivo cargado</p>
                            @endif
                            <p class="text-xs text-base-content/50 mt-1">Para persona natural sin RUT, puede omitir.</p>
                            @error('documento_rut_pdf') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>
                </div>

                {{-- ===== DATOS ADICIONALES ===== --}}
                <div class="divider text-xs font-semibold text-primary/70 uppercase mt-6">Datos Adicionales</div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Zona del Vendedor</label>
                        <input type="text" wire:model="zona" class="input w-full" placeholder="Zona de cobertura" />
                    </div>

                    <div class="flex items-center justify-between p-3 bg-base-200 rounded-lg">
                        <div>
                            <p class="text-sm font-medium">Cliente de Contado</p>
                            <p class="text-xs text-base-content/50">¿El cliente paga de contado?</p>
                        </div>
                        <input type="checkbox" wire:model="cliente_contado" class="toggle toggle-primary" />
                    </div>
                </div>

                <div class="mt-4">
                    <label class="block text-sm font-medium mb-1">Observaciones</label>
                    <textarea wire:model="observaciones" class="textarea w-full" rows="3" placeholder="Notas u observaciones adicionales (opcional)"></textarea>
                </div>

                {{-- ===== CONFIRMACIÓN ===== --}}
                <div class="mt-6 p-4 bg-warning/10 border border-warning/30 rounded-lg">
                    <label class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" wire:model="confirmacion_datos" class="checkbox checkbox-primary mt-0.5" />
                        <span class="text-sm">
                            <strong>Confirmo</strong> que los datos registrados son verídicos, que la firma fue realizada
                            por el cliente en mi presencia y que la información suministrada corresponde a la realidad del negocio.
                        </span>
                    </label>
                    @error('confirmacion_datos') <p class="text-error text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>
        @endif

        {{-- ========== BOTONES DE NAVEGACIÓN ========== --}}
        <div class="flex justify-between items-center mt-6">
            @if($paso > 1)
                <button wire:click="pasoAnterior" class="btn btn-outline btn-primary gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                    Anterior
                </button>
            @else
                <div></div>
            @endif

            @if($paso < $totalPasos)
                <button wire:click="siguientePaso" class="btn btn-primary gap-1">
                    Siguiente
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            @else
                <button wire:click="guardarCliente"
                        class="btn btn-success gap-1"
                        wire:loading.attr="disabled"
                        wire:target="guardarCliente">
                    <span wire:loading.remove wire:target="guardarCliente" class="flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                        </svg>
                        Guardar Cliente
                    </span>
                    <span wire:loading wire:target="guardarCliente" class="flex items-center gap-1">
                        <span class="loading loading-spinner loading-sm"></span>
                        Guardando...
                    </span>
                </button>
            @endif
        </div>
    </div>

    @else
    {{-- ========== ESTADO: GUARDADO EXITOSO ========== --}}
    <div class="min-h-[70vh] flex items-center justify-center px-4">
        <div class="card bg-base-100 shadow-2xl max-w-md w-full">
            <div class="card-body items-center text-center py-10">
                <div class="w-20 h-20 bg-success/15 rounded-full flex items-center justify-center mb-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-success" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h2 class="text-2xl font-bold text-success">¡Cliente Registrado!</h2>
                <p class="text-base-content/70 mt-2">
                    El cliente ha sido registrado exitosamente.
                </p>
                <div class="badge badge-primary badge-lg mt-2">ID #{{ $clienteGuardadoId }}</div>

                <div class="card-actions mt-6 flex-col gap-2 w-full">
                    <button wire:click="nuevoCliente" class="btn btn-primary w-full gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        Registrar Otro Cliente
                    </button>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline w-full">
                        Volver al Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif

    {{-- ========== ESTILOS: Animación fade-in ========== --}}
    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in {
            animation: fadeIn 0.3s ease-out;
        }
    </style>
</div>
