FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nodejs \
    npm \
    zip \
    unzip \
    curl \
    git \
    bash \
    postgresql-dev \
    mariadb-dev \
    oniguruma-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /app

COPY composer.json ./
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

COPY . .
RUN rm -f .env
RUN composer dump-autoload --optimize
RUN npm install --legacy-peer-deps && npm run build
RUN rm -rf node_modules
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

CMD php artisan key:generate --force 2>/dev/null; \
    php artisan migrate --force 2>/dev/null || true; \
    php artisan serve --host=0.0.0.0 --port=${PORT:-8080}
