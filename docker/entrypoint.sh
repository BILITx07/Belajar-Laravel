#!/bin/sh
set -e

PORT=${PORT:-80}

sed -i "s/\${PORT}/$PORT/g" /etc/nginx/http.d/default.conf 2>/dev/null || sed -i "s/\${PORT}/$PORT/g" /etc/nginx/conf.d/default.conf 2>/dev/null || true

mkdir -p /var/www/storage/framework/cache/data \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/bootstrap/cache \
         /var/www/database

touch /var/www/database/database.sqlite 2>/dev/null || true

chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database
chmod -R 775 /var/www/storage /var/www/bootstrap/cache /var/www/database

php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

echo "Menjalankan migrasi database..."
php artisan migrate --force || true

php artisan storage:link || true

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx on port $PORT..."
exec nginx -g "daemon off;"
