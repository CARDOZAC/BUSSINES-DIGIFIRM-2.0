@echo off
title DIGIFIRM - Migrar y Sembrar
set PHP_PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe

if not exist "%PHP_PATH%" (
    echo [ERROR] No se encontro PHP 8.3.
    echo.
    echo SOLUCION: En Laragon, vaya a:
    echo   Menu - PHP - Version - php-8.3.30
    echo.
    echo Luego ejecute en la terminal:
    echo   php artisan migrate
    echo   php artisan db:seed
    pause
    exit /b 1
)

cd /d "%~dp0"

echo Ejecutando migraciones...
"%PHP_PATH%" artisan migrate --force
if errorlevel 1 (
    echo [ERROR] Fallo la migracion.
    pause
    exit /b 1
)

echo.
echo Ejecutando seeders...
"%PHP_PATH%" artisan db:seed --force
if errorlevel 1 (
    echo [ERROR] Fallo el seeder.
    pause
    exit /b 1
)

echo.
echo [OK] Migraciones y seeders completados correctamente.
pause
