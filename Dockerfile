FROM php:8.3-apache

# 1. Instalar dependencias del sistema y extensiones de PHP
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 2. Instalar Node.js (necesario para compilar los assets con Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 3. Traer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Configurar el directorio de trabajo
WORKDIR /var/www/html

# 5. Copiar los archivos del proyecto
COPY . .

# 6. Instalar dependencias de PHP (Laravel)
RUN composer install --optimize-autoloader --no-dev

# 7. Instalar dependencias de JS y compilar assets con Vite
# Esto solucionará el error: Vite manifest not found
RUN npm install && npm run build

# 8. Permisos para carpetas de almacenamiento y caché
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 9. Configurar Apache para que apunte a la carpeta public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -i 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 10. Exponer el puerto y lanzar Apache
EXPOSE 80
CMD ["apache2-foreground"]