#!/bin/bash

# Create .env from example if missing
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env; then
    php artisan key:generate --force
fi

# Start Laravel server immediately in background
php artisan serve --host=0.0.0.0 --port=${PORT:-8080} &

# Run setup in background
(
    sleep 5
    php artisan migrate --force 2>/dev/null || true
    php artisan config:cache 2>/dev/null || true
    php artisan route:cache 2>/dev/null || true
    php artisan view:cache 2>/dev/null || true
) &

wait
