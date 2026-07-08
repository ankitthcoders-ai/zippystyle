#!/bin/bash

# Generate app key if not set
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = " " ]; then
    php artisan key:generate --force
fi

# Run database migrations (allow failure if no DB yet)
php artisan migrate --force 2>/dev/null || true

# Cache config, routes, views (allow failure)
php artisan config:cache 2>/dev/null || true
php artisan route:cache 2>/dev/null || true
php artisan view:cache 2>/dev/null || true

# Start Laravel development server
php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
