#!/bin/sh

# Run composer install if the vendor directory does not exist
if [ ! -d "/var/www/vendor" ]; then
    composer install --no-dev --optimize-autoloader
fi

# Run Laravel specific commands
php artisan key:generate --force
php artisan config:cache
php artisan route:cache

# Execute the original command passed to the container
exec "$@"
