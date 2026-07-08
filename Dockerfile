FROM php:8.4-fpm-alpine

# Install system dependencies
RUN apk add --no-cache \
    nginx \
    supervisor \
    nodejs \
    npm \
    zip \
    unzip \
    curl \
    git \
    postgresql-dev \
    mariadb-dev \
    onig-dev \
    $PHPIZE_DEPS

# Install PHP extensions
RUN docker-php-ext-install pdo pdo_pgsql pdo_mysql mbstring sockets bcmath

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /app

# Copy composer file first for layer caching
COPY composer.json ./

# Install PHP dependencies (no scripts yet since app isn't fully copied)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# Copy the rest of the application
COPY . .

# Run production scripts (package discovery, etc.)
RUN composer dump-autoload --optimize

# Install Node dependencies and build assets
RUN npm ci && npm run build

# Remove node_modules after build (not needed at runtime)
RUN rm -rf node_modules

# Configure nginx
COPY docker/nginx.conf /etc/nginx/http.d/default.conf

# Configure supervisord
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

# Create runtime directories and set permissions
RUN mkdir -p /run/nginx && \
    chmod -R 775 storage bootstrap/cache && \
    chown -R www-data:www-data storage bootstrap/cache

EXPOSE 8080

ENTRYPOINT ["/app/start.sh"]
