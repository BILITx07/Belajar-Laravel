#!/bin/sh
set -e

PORT=${PORT:-80}

# Bersihkan karakter aneh atau trailing parenthesis dari Railway variables
if [ -n "$MYSQLDATABASE" ]; then export MYSQLDATABASE=$(echo "$MYSQLDATABASE" | tr -d "')\" "); fi
if [ -n "$MYSQL_URL" ]; then export MYSQL_URL=$(echo "$MYSQL_URL" | tr -d "')\" "); fi
if [ -n "$MYSQL_URI" ]; then export MYSQL_URI=$(echo "$MYSQL_URI" | tr -d "')\" "); fi
if [ -n "$DB_DATABASE" ]; then export DB_DATABASE=$(echo "$DB_DATABASE" | tr -d "')\" "); fi
if [ -n "$DB_URL" ]; then export DB_URL=$(echo "$DB_URL" | tr -d "')\" "); fi

# Update Nginx listen port
sed -i "s/\${PORT}/$PORT/g" /etc/nginx/http.d/default.conf 2>/dev/null || sed -i "s/\${PORT}/$PORT/g" /etc/nginx/conf.d/default.conf 2>/dev/null || true

# Pastikan konfigurasi PHP-FPM mencatat error worker ke stderr
sed -i 's/;catch_workers_output = .*/catch_workers_output = yes/' /usr/local/etc/php-fpm.d/www.conf 2>/dev/null || true
sed -i 's/;decorate_workers_output = .*/decorate_workers_output = no/' /usr/local/etc/php-fpm.d/www.conf 2>/dev/null || true

# Buat direktori yang diperlukan
mkdir -p /var/www/storage/framework/cache/data \
         /var/www/storage/framework/sessions \
         /var/www/storage/framework/views \
         /var/www/storage/logs \
         /var/www/bootstrap/cache \
         /var/www/database

# Buat file .env default jika belum ada
if [ ! -f /var/www/.env ]; then
    cat <<EOF > /var/www/.env
APP_NAME=FEWS-BWS
APP_ENV=production
APP_KEY=${APP_KEY:-base64:ydrAfWLB+8RfrSypgK/Koz5avVRebgv08y/IMFu4buE=}
APP_DEBUG=true
APP_URL=https://belajar-laravel-production.up.railway.app
SESSION_DRIVER=file
CACHE_STORE=file
LOG_CHANNEL=stderr
EOF
fi

# Buat database sqlite jika belum ada
touch /var/www/database/database.sqlite 2>/dev/null || true

# Clear cache lama
php artisan config:clear || true
php artisan route:clear || true
php artisan view:clear || true

# Jalankan migrasi dan seeder
echo "Menjalankan migrasi database..."
php artisan migrate --force || true

echo "Menjalankan seeder user admin..."
php artisan db:seed --class=UserSeeder --force || true

# Symlink storage
php artisan storage:link || true

# PENTING: Set permission ke www-data SETELAH migrasi dan artisan selesai dijalankan oleh root
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database /var/www/.env
chmod -R 777 /var/www/storage /var/www/bootstrap/cache /var/www/database
chmod 664 /var/www/.env

echo "Starting PHP-FPM..."
php-fpm -D

echo "Starting Nginx on port $PORT..."
exec nginx -g "daemon off;"
