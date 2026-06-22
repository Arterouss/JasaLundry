#!/bin/bash

echo "Starting deployment scripts..."

# Jalankan migrasi database otomatis saat deploy
echo "Running migrations..."
php artisan migrate --force

# Bersihkan dan cache konfigurasi agar lebih cepat
echo "Caching configurations..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# (Opsional) Storage link jika butuh menampilkan gambar
php artisan storage:link

echo "Starting Apache server..."
# Jalankan Apache (Server Laravel)
apache2-foreground
