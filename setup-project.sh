#!/bin/sh
ls
echo "================================================="
echo "Building docker images"
CP ./backend/.env.example ./backend/.env
docker compose up --build -d
echo "================================================="
echo "Setting up docker containers:"
echo "- Install composer packages"
echo "- Generate laravel application key"
echo "- Migrate and seed data"
echo "- Setup scheduled task"

docker exec -it app-php bash -c "composer install; php artisan key:generate; php artisan migrate --seed"
docker exec -i app-php php artisan schedule:run >> /dev/null 2>&1
echo "================================================="
echo "===================FINISHED======================"
