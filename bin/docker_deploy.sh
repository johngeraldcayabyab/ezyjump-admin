#!/bin/bash

#CREATE USER 'laraveluser'@'%';
#GRANT ALL PRIVILEGES ON laravel.* TO 'laraveluser'@'%';
#SET PASSWORD FOR 'laraveluser'@'%' = 'testpass';

docker compose -f docker-compose.prod.yml exec app composer install --no-interaction --prefer-dist --optimize-autoloader --no-dev --no-progress --no-plugins --no-scripts --no-ansi
docker compose -f docker-compose.prod.yml exec app npm install --production --omit=dev --prefer-offline --no-audit --progress=false
docker compose -f docker-compose.prod.yml exec app npm install laravel-mix@latest
docker compose -f docker-compose.prod.yml exec app npm install --save-dev vite laravel-vite-plugin
docker compose -f docker-compose.prod.yml exec app npm run build
docker compose -f docker-compose.prod.yml exec app composer dump-autoload -o

docker compose -f docker-compose.prod.yml exec app php artisan key:generate --no-interaction --force
docker compose -f docker-compose.prod.yml exec app php artisan migrate:fresh --seed --no-interaction --force
docker compose -f docker-compose.prod.yml exec app php artisan optimize:clear
docker compose -f docker-compose.prod.yml exec app php artisan storage:link
