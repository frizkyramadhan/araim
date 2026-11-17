@echo off
REM Start araim.v2 on PHP 8.1 (Laravel 8) without switching Laragon global PHP.
set PHP81=C:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe
set HOST=127.0.0.1
set PORT=8078
cd /d C:\laragon\www\araim.v2
echo.
echo  araim.v2  http://%HOST%:%PORT%
echo  PHP: %PHP81%
echo  Press Ctrl+C to stop
echo.
"%PHP81%" artisan serve --host=%HOST% --port=%PORT%
