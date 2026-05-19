FROM php:8.4-fpm AS php-base

# Install system dependencies
RUN apt-get update && apt-get install -y \
    unzip \
    curl \
    libsqlite3-dev \
    nginx \
    supervisor \
    && docker-php-ext-install pdo pdo_sqlite \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js 20
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/frontend

# Copy composer files first for layer caching
COPY composer.json composer.lock ./

# Install PHP dependencies (include dev for artisan commands)
RUN composer install --optimize-autoloader --no-interaction --no-scripts

# Copy package files for node layer caching
COPY package.json package-lock.json* ./

RUN npm install

# Copy the full application
COPY . .

# Run composer scripts (post-autoload-dump etc.)
RUN composer run-script post-autoload-dump || true

# Build frontend assets
RUN npm run build

# Ensure storage directories exist and have correct permissions
RUN mkdir -p storage/framework/{cache,sessions,views} \
             storage/logs \
             bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data storage bootstrap/cache

# SQLite database
RUN touch database/database.sqlite \
    && chmod 664 database/database.sqlite \
    && chown www-data:www-data database/database.sqlite

# Nginx config
COPY docker/nginx.conf /etc/nginx/sites-available/default

# Supervisor config to run nginx + php-fpm together
COPY docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

EXPOSE 8001

# Entrypoint runs migrations then starts nginx + php-fpm via supervisor
COPY docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

CMD ["/entrypoint.sh"]
