#!/bin/sh
set -e

cd /var/www/html

if [ ! -f .env ]; then
    cp .env.example .env
fi

if ! grep -q '^APP_KEY=base64:' .env; then
    php artisan key:generate --force
fi

echo "Waiting for database ${DB_HOST}:${DB_PORT}..."
until php -r 'new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT").";dbname=".getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD"));' 2>/dev/null; do
    sleep 2
done

php artisan migrate --force

# Seeders are not idempotent, so seed only an empty database.
if php -r '$db = new PDO("mysql:host=".getenv("DB_HOST").";port=".getenv("DB_PORT").";dbname=".getenv("DB_DATABASE"), getenv("DB_USERNAME"), getenv("DB_PASSWORD")); exit($db->query("SELECT COUNT(*) FROM users")->fetchColumn() > 0 ? 1 : 0);'; then
    php artisan db:seed --force
fi

if [ ! -e public/storage ]; then
    php artisan storage:link
fi
chown -R www-data:www-data storage bootstrap/cache

exec "$@"
