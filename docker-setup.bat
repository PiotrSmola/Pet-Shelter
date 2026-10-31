@echo off
setlocal
cd /d "%~dp0"

if "%APP_PORT%"=="" set APP_PORT=8000
if "%PMA_PORT%"=="" set PMA_PORT=8081

where docker >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker nie jest zainstalowany. Pobierz Docker Desktop: https://www.docker.com/products/docker-desktop/
    pause
    exit /b 1
)

docker info >nul 2>&1
if %errorlevel% neq 0 (
    echo Docker nie jest uruchomiony. Wlacz Docker Desktop i sprobuj ponownie.
    pause
    exit /b 1
)

echo Budowanie i uruchamianie kontenerow...
docker compose up -d --build
if %errorlevel% neq 0 (
    echo Nie udalo sie uruchomic kontenerow.
    pause
    exit /b 1
)

echo Czekanie na aplikacje (pierwsze uruchomienie moze potrwac kilka minut)...
set /a tries=0
:wait
set /a tries+=1
curl -s -o nul http://localhost:%APP_PORT% && goto ready
if %tries% geq 90 (
    echo Aplikacja nie odpowiada. Sprawdz logi: docker compose logs app
    pause
    exit /b 1
)
ping -n 3 127.0.0.1 >nul
goto wait

:ready
echo.
echo Aplikacja:  http://localhost:%APP_PORT%
echo phpMyAdmin: http://localhost:%PMA_PORT%
echo Zatrzymanie: docker compose down
start http://localhost:%APP_PORT%
endlocal
