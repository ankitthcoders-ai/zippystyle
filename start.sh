#!/bin/bash

# Create .env from example if missing
if [ ! -f .env ]; then
    cp .env.example .env
fi

# Generate app key if not set
if ! grep -q "APP_KEY=base64:" .env 2>/dev/null; then
    php artisan key:generate --force
fi

# Run setup (allow failures)
php artisan migrate --force 2>/dev/null || true
php artisan config:cache 2>/dev/null || true

# Start Laravel server in foreground
exec php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
