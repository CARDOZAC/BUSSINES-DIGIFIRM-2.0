# Digital Clientes R&V - Instrucciones de Inicio

## Requisitos previos

| Componente | Versión mínima | Notas |
|---|---|---|
| PHP | 8.3+ | Ruta: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\` |
| MySQL/MariaDB | 5.7+ / 10.4+ | Puerto configurado: 8081 (Laragon) |
| Composer | 2.x | Para gestión de dependencias PHP |
| Node.js | 18+ | Para compilar assets (Vite + Tailwind + DaisyUI) |
| npm | 9+ | Incluido con Node.js |

---

## 1. Instalación inicial (primera vez)

```bash
cd c:\laragon\www\digital-clientes-rv

# Instalar dependencias PHP
composer install

# Instalar dependencias Node.js
npm install

# Copiar archivo de entorno (si no existe)
copy .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Ejecutar migraciones y seeders
php artisan migrate --seed

# Compilar assets frontend
npm run build
```

### Usuarios predeterminados (seeders)

| Rol | Email | Contraseña |
|---|---|---|
| Admin Cartera | `admin@gruporv.com` | `Rv@2026!` |
| Vendedor | `vendedor@gruporv.com` | `Rv@2026!` |

---

## 2. Inicio rápido del servidor

### Opción A: Archivo .bat (recomendado)

Hacer doble clic en `start-server.bat` en la raíz del proyecto.
El script detecta PHP 8.3, muestra las IPs disponibles e inicia el servidor accesible por red.

### Opción B: Comando manual

```bash
cd c:\laragon\www\digital-clientes-rv

# Usar PHP 8.3 explícitamente
C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe artisan serve --host=0.0.0.0 --port=8000
```

### Opción C: Laragon (Apache)

1. Abrir Laragon
2. Clic derecho > PHP > Version > `php-8.3.30`
3. Iniciar Apache/Nginx
4. Acceder a `http://digital-clientes-rv.test`

---

## 3. Acceso desde otros dispositivos (celular, otro PC)

1. Asegurarse de que todos los dispositivos estén en la **misma red WiFi/LAN**.
2. Iniciar el servidor con `--host=0.0.0.0` (el `.bat` ya lo hace).
3. Obtener la IP del servidor:
   ```bash
   ipconfig
   ```
   Buscar la línea `Dirección IPv4`, por ejemplo: `192.168.1.100`
4. Desde el celular o PC, abrir el navegador y escribir:
   ```
   http://192.168.1.100:8000
   ```
5. Si no conecta, verificar el **Firewall de Windows**:
   - Panel de Control > Firewall > Permitir app a través del firewall
   - Agregar `php.exe` (ruta: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe`)
   - O ejecutar en PowerShell (como administrador):
     ```powershell
     New-NetFirewallRule -DisplayName "PHP Artisan Serve" -Direction Inbound -Action Allow -Protocol TCP -LocalPort 8000
     ```

---

## 4. Flujo de uso de la aplicación

1. **Login**: Ingresar con credenciales de vendedor
2. **Nuevo Cliente**: Navegar a `/clientes/nuevo` o usar el enlace en la barra
3. **Wizard de 4 pasos**:
   - **Paso 1**: Seleccionar empresa y tipo de solicitud
   - **Paso 2**: Información general del cliente (documento, nombre, dirección, etc.)
   - **Paso 3**: Información tributaria (CIIU, IVA, retenciones)
   - **Paso 4**: Firma digital en canvas + foto opcional + confirmación
4. **Guardar**: Se genera automáticamente el PDF formato CTA-FMT-001 con firma, QR y hash

---

## 5. Estructura del proyecto

```
digital-clientes-rv/
├── app/
│   ├── Livewire/ClienteWizard.php     # Wizard principal
│   ├── Models/                         # Empresa, Cliente, User, ActivityLog
│   └── Services/
│       ├── PdfClienteService.php       # Genera PDF CTA-FMT-001
│       ├── CloudinaryService.php       # Sube archivos a Cloudinary
│       └── ActivityLogService.php      # Registro de actividad
├── resources/views/
│   ├── livewire/cliente-wizard.blade.php  # Vista del wizard
│   └── pdf/formato-cta-fmt-001.blade.php  # Template HTML del PDF
├── routes/web.php                      # Rutas de la app
├── database/
│   ├── migrations/                     # Estructura de BD
│   └── seeders/                        # Datos iniciales
├── start-server.bat                    # Inicio rápido del servidor
└── INSTRUCCIONES.md                    # Este archivo
```

---

## 6. Comandos útiles

```bash
# Compilar assets en modo desarrollo (con hot reload)
npm run dev

# Compilar assets para producción
npm run build

# Limpiar cachés
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Ver rutas registradas
php artisan route:list

# Crear usuario en tinker
php artisan tinker
> App\Models\User::create(['name'=>'Nombre','email'=>'correo@test.com','password'=>bcrypt('clave'),'empresa_id'=>1,'active'=>true]);

# Re-ejecutar seeders
php artisan db:seed
```

---

## 7. Configuración de Cloudinary (para PDFs y fotos)

Las credenciales se configuran en `.env`:

```env
CLOUDINARY_URL=cloudinary://API_KEY:API_SECRET@CLOUD_NAME
CLOUDINARY_UPLOAD_PRESET=clientes_rv
```

Si no se configura Cloudinary, los PDFs se generan localmente en `storage/app/private/pdfs/` pero no se suben a la nube. La foto del cliente es opcional.

---

## 8. Solución de problemas

| Problema | Solución |
|---|---|
| Error "PHP >= 8.3.0 required" | Usar PHP 8.3: `C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe` |
| No carga estilos CSS | Ejecutar `npm run build` para compilar assets |
| No conecta desde otro dispositivo | Verificar firewall y que esté en la misma red |
| Error de base de datos | Verificar MySQL corriendo en puerto 8081 |
| La firma no aparece | Asegurarse de firmar antes de hacer clic en Guardar |
| Error al generar PDF | Verificar que la carpeta `storage/app/private/pdfs/` existe |
