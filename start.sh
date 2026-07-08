#!/bin/bash

# Run database migrations
php artisan migrate --force

# Clear and cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Start Laravel development server
php artisan serve --host=0.0.0.0 --port=$PORT
