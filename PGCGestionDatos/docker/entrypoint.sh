#!/bin/bash

set -e

# Esperar a que MySQL esté listo
echo "Esperando a que MySQL esté disponible..."
until php artisan db:show > /dev/null 2>&1; do
    echo "MySQL no está listo - esperando..."
    sleep 2
done

echo "MySQL está disponible. Iniciando aplicación..."

# Configurar permisos (solo si es necesario)
if [ ! -f /var/www/html/storage/logs/laravel.log ]; then
    touch /var/www/html/storage/logs/laravel.log
fi

# Ejecutar migraciones (descomenta si quieres que se ejecuten automáticamente)
# php artisan migrate --force

# Cache de configuración para producción
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "Aplicación lista. Iniciando PHP-FPM..."

# Ejecutar el comando pasado como argumento
exec "$@"
