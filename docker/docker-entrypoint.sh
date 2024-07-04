#!/bin/sh

# Run composer install if the vendor directory does not exist
if [ ! -d "/var/www/vendor" ]; then
    echo "Vendor directory not found. Installing composer dependencies..."
    composer install --optimize-autoloader
    if [ $? -ne 0 ]; then
        echo "Composer install failed."
        exit 1
    fi
else
    echo "Vendor directory already exists."
fi

# Debug output to ensure dev dependencies are installed
echo "Contents of /var/www/vendor/bin/ after composer install:"
ls -l /var/www/vendor/bin/

# Run Laravel specific commands
php artisan key:generate --force
php artisan config:cache
php artisan route:cache

# Migrate PHPUnit configuration
echo "Checking PHPUnit installation..."
if [ -f "/var/www/vendor/bin/phpunit" ]; then
    echo "PHPUnit binary found."
    echo "Migrating PHPUnit configuration..."
    /var/www/vendor/bin/phpunit --migrate-configuration
else
    echo "PHPUnit binary not found in /var/www/vendor/bin/. Contents of /var/www/vendor/bin/:"
    ls -l /var/www/vendor/bin/
    echo "Ensure that PHPUnit is correctly installed."
    exit 1
fi

# Execute the original command passed to the container
exec "$@"
