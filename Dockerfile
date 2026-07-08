FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nodejs \
    npm \
    zip \
    unzip \
    curl \
    git \
    postgresql-dev \
    mariadb-dev \
    oniguruma-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer file first for layer caching
COPY composer.json ./

# Install PHP dependencies
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of the application
COPY . .

# Run production scripts
RUN composer dump-autoload --optimize

# Install Node dependencies and build assets
RUN npm install --legacy-peer-deps && npm run build

# Remove node_modules after build (not needed at runtime)
RUN rm -rf node_modules

# Set permissions
RUN chmod -R 775 storage bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/app/start.sh"]
