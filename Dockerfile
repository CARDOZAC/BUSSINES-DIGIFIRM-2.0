FROM php:8.3-apache

# 1. Instalar dependencias del sistema y extensiones de PHP necesarias
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 2. Instalar Node.js (Versión 20 LTS para compatibilidad total con Vite)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# 3. Obtener la versión más reciente de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Establecer el directorio de trabajo
WORKDIR /var/www/html

# 5. Copiar el código de tu proyecto
COPY . .

# 6. Instalar dependencias de PHP (Laravel)
RUN composer install --optimize-autoloader --no-dev

# 7. Instalar dependencias de Node y compilar assets (Vite)
# Esto soluciona el error del manifest.json no encontrado
RUN npm install && npm run build

# 8. Configurar permisos de forma recursiva
# Asegura que Apache pueda escribir en storage y bootstrap/cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage \
    && chmod -R 775 /var/www/html/bootstrap/cache

# 9. Configurar el Document Root de Apache hacia la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -i 's|/var/www/html|${APACHE_DOCUMENT_ROOT}|g' /etc/apache2/sites-available/000-default.conf
RUN a2enmod rewrite

# 10. Exponer el puerto 80
EXPOSE 80

# 11. Comando de inicio: Limpia cachés de Laravel y arranca Apache
# El flag --force es por si decides correr migraciones aquí
CMD php artisan migrate --force && \
    php artisan config:clear && \
    php artisan view:clear && \
    php artisan route:clear && \
    apache2-foreground