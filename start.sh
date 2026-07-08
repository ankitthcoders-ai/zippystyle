#!/bin/bash

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = " " ]; then
    php artisan key:generate --force
fi

# Run database migrations
php artisan migrate --force

# Cache config, routes, views
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Set proper permissions
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache

# Start supervisord (runs nginx + php-fpm)
exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf
