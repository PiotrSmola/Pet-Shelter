%systemDrive%\xampp\mysql\bin\mysql -uroot -e "CREATE DATABASE IF NOT EXISTS petShelter;"

if %errorlevel% neq 0 msg %username% "Nie udalo sie utworzyc bazy danych." && exit /b %errorlevel%

php -r "copy('.env.example', '.env');"

call composer install

call composer require stripe/stripe-php

call php artisan key:generate

call php artisan storage:link

call php artisan migrate:fresh --seed

call composer require --dev barryvdh/laravel-ide-helper

call php artisan ide-helper:generate

call php artisan serve

start http://127.0.0.1:8000

code .
