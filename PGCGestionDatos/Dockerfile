# Dockerfile multi-stage para Laravel con PHP 8.2
# Stage 1: Builder
FROM php:8.2-fpm AS builder

# Instalar dependencias del sistema
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Instalar Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Crear directorio de trabajo
WORKDIR /var/www/html

# Copiar archivos de composer primero (para cache de layers)
COPY composer.json composer.lock ./

# Instalar dependencias de PHP sin scripts ni dev dependencies
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist

# Copiar el resto del código
COPY . .

# Copiar script de entrypoint
COPY docker/entrypoint.sh /tmp/entrypoint.sh

# Generar autoloader optimizado
RUN composer dump-autoload --optimize --no-dev

# Stage 2: Producción
FROM php:8.2-fpm

# Instalar solo las extensiones necesarias para producción
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Crear usuario para la aplicación
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Copiar código desde el builder
COPY --from=builder --chown=www:www /var/www/html /var/www/html

# Copiar script de entrypoint desde el builder
COPY --from=builder /tmp/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Configurar permisos
WORKDIR /var/www/html
RUN chown -R www:www /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

USER www

EXPOSE 9000

CMD ["php-fpm"]
