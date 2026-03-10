@echo off
title DIGIFIRM - Artisan
set PHP_PATH=C:\laragon\bin\php\php-8.3.30-Win32-vs16-x64\php.exe

if not exist "%PHP_PATH%" (
    echo [ERROR] No se encontro PHP 8.3 en: %PHP_PATH%
    echo Cambie Laragon a PHP 8.3: Menu - PHP - Version - php-8.3.30
    pause
    exit /b 1
)

cd /d "%~dp0"
"%PHP_PATH%" artisan %*
pause
