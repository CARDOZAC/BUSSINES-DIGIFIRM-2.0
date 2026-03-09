@echo off
title Digital Clientes R^&V - Servidor
echo ============================================
echo   Digital Clientes R^&V - Inicio del Servidor
echo ============================================
echo.

set PHP_PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe

REM Verificar que PHP existe
if not exist "%PHP_PATH%" (
    echo [ERROR] No se encontro PHP 8.3 en: %PHP_PATH%
    echo Verifique la ruta de PHP en este archivo.
    pause
    exit /b 1
)

echo [OK] PHP encontrado: 
"%PHP_PATH%" -v | findstr /i "PHP"
echo.

REM Obtener IP local
echo Direcciones IP disponibles:
echo -------------------------------------------
for /f "tokens=2 delims=:" %%a in ('ipconfig ^| findstr /i "IPv4"') do (
    echo   %%a
)
echo -------------------------------------------
echo.

echo Iniciando servidor en 0.0.0.0:8000 ...
echo Acceso local:    http://localhost:8000
echo Acceso por red:  http://[TU-IP]:8000
echo.
echo Presione Ctrl+C para detener el servidor.
echo ============================================
echo.

"%PHP_PATH%" artisan serve --host=0.0.0.0 --port=8000
pause
