#!/bin/bash
set -e

cd /var/www/frontend

# Sync variables al .env
sed -i "s|BACKEND_API_URL=.*|BACKEND_API_URL=${BACKEND_API_URL:-http://backend:8000/api}|g" .env
sed -i "s|BACKEND_BASE_URL=.*|BACKEND_BASE_URL=${BACKEND_BASE_URL:-http://backend:8000}|g" .env
sed -i "s|APP_URL=.*|APP_URL=${APP_URL:-http://localhost:8001}|g" .env
sed -i "s|APP_KEY=.*|APP_KEY=${APP_KEY:-}|g" .env

# Permisos críticos — el volumen sobreescribe los permisos de la imagen
chown -R www-data:www-data database/
chmod 775 database/
chmod 664 database/database.sqlite

php artisan config:clear
php artisan migrate --force

exec /usr/bin/supervisord -c /etc/supervisor/conf.d/supervisord.conf