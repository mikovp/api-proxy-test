#!/usr/bin/env sh
if [ ! -d "/var/www/vendor" ]; then
    echo "Copy .env file"
    cp /var/www/.env.example /var/www/.env
    echo "Installing dependencies..."
    composer install --prefer-dist --no-progress --no-suggest --no-interaction
    php artisan key:generate
    php artisan migrate --force
    php artisan l5-swagger:generate
    exit 1
fi
set -e

role=${CONTAINER_ROLE:-app}
env=${APP_ENV:-production}

if [ "$env" != "local" ]; then
    echo "Caching configuration..."
    (cd /var/www/ && php artisan config:cache && php artisan route:cache && php artisan view:cache)
fi

if [ "$role" = "app" ]; then
    exec php-fpm
elif [ "$role" = "scheduler" ]; then
    echo "Queue role"
    while [ true ]; do
        php /var/www/artisan schedule:run --verbose --no-interaction &
        sleep 60
    done
elif [ "$role" = "queue" ]; then
    echo "Running the queue..."
    php /var/www/artisan queue:work --verbose --tries=3 --timeout=90
else
    echo "Could not match the container role \"$role\""
    exit 1
fi