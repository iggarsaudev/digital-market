FROM php:8.2-apache

# 1. Instalamos las herramientas del sistema y el driver de PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql pgsql

# 2. Activamos el módulo rewrite de Apache (vital para las rutas de Laravel)
RUN a2enmod rewrite

# 3. Le decimos a Apache que la carpeta pública de Laravel es la principal
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 4. Traemos Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiamos todo tu código de Laravel al contenedor
COPY . /var/www/html

# 6. Instalamos las dependencias de Laravel para producción
RUN composer install --no-dev --optimize-autoloader

# 7. Damos permisos a las carpetas que Laravel necesita modificar
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Exponemos el puerto 80 para que Render pueda conectar
EXPOSE 80

# 9. COMANDO MÁGICO: Migraciones, Seeders y arrancar servidor
CMD php artisan migrate --force && php artisan db:seed --force && apache2-foreground