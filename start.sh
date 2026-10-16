#!/bin/sh

# Buat file database SQLite jika belum ada sebagai fallback
mkdir -p database
touch database/database.sqlite 2>/dev/null || true

# Clear cache agar konfigurasi selalu fresh
php artisan config:clear || true

# Jalankan migrasi database
php artisan migrate --force || true

# Jalankan server
echo "Starting Laravel server on port ${PORT:-8000}..."
exec php artisan serve --host=0.0.0.0 --port="${PORT:-8000}"
