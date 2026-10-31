#!/bin/sh
set -e
cd "$(dirname "$0")"

APP_PORT="${APP_PORT:-8000}"
PMA_PORT="${PMA_PORT:-8081}"

if ! command -v docker >/dev/null 2>&1; then
    echo "Docker is not installed: https://docs.docker.com/get-docker/"
    exit 1
fi

if ! docker info >/dev/null 2>&1; then
    echo "Docker is not running. Start the Docker daemon and try again."
    exit 1
fi

echo "Building and starting containers..."
docker compose up -d --build

echo "Waiting for the application (first run can take a few minutes)..."
tries=0
until curl -s -o /dev/null "http://localhost:${APP_PORT}"; do
    tries=$((tries + 1))
    if [ "$tries" -ge 90 ]; then
        echo "Application is not responding. Check logs: docker compose logs app"
        exit 1
    fi
    sleep 2
done

echo
echo "App:        http://localhost:${APP_PORT}"
echo "phpMyAdmin: http://localhost:${PMA_PORT}"
echo "Stop:       docker compose down"

if command -v xdg-open >/dev/null 2>&1; then
    xdg-open "http://localhost:${APP_PORT}" >/dev/null 2>&1 || true
elif command -v open >/dev/null 2>&1; then
    open "http://localhost:${APP_PORT}"
fi
