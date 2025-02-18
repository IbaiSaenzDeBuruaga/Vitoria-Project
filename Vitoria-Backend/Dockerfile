# Usa una imagen base de PHP con Apache (Debian)
FROM php:8.2-apache

# Instala dependencias necesarias (para PDO y MySQL, y otras)
RUN apt-get update && apt-get install -y \
    $PHPIZE_DEPS \
    libzip-dev \
    zip \
    && docker-php-ext-install pdo pdo_mysql zip

# Instala Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Establece el directorio de trabajo en el contenedor
WORKDIR /var/www/html

# Copia todos los archivos de la aplicación Laravel
COPY . /var/www/html

# Instala las dependencias de Laravel usando Composer
RUN composer install 

# Establece la propiedad de la carpeta bootstrap/cache al usuario www-data
RUN chown -R www-data:www-data bootstrap/cache

# Establece la propiedad de la carpeta storage al usuario www-data
RUN chown -R www-data:www-data storage

# Establece la propiedad de los archivos dentro de la carpeta storage al usuario www-data, incluyendo la carpeta app/public
RUN chown -R www-data:www-data storage/app/public

# Establece permisos para la carpeta storage y sus subcarpetas
RUN chmod -R 775 /var/www/html/storage

# Diagnóstico: Verifica que el directorio storage/app/public existe y sus permisos
RUN echo "Verificando existencia y permisos de storage/app/public:"
RUN ls -l storage/app
RUN ls -l storage/app/public

# Diagnóstico: Verifica permisos del directorio public
RUN echo "Verificando permisos del directorio public:"
RUN ls -l public

# Intenta crear el directorio storage/app/public si no existe (aunque ya debería existir al copiar el proyecto)
RUN echo "Creando el directorio storage/app/public (si no existe):"
RUN mkdir -p storage/app/public

# Ejecuta el comando para crear el enlace simbólico
RUN echo "Creando enlace simbólico:"
RUN php artisan storage:link

# Expone el puerto 80
EXPOSE 80

# Configura Apache para que sirva Laravel (modificar el VirtualHost)
RUN sed -i 's!/var/www/html!/var/www/html/public!g' /etc/apache2/sites-available/000-default.conf

# Habilita mod_rewrite para URLs amigables de Laravel
RUN a2enmod rewrite


# ----------------------------------- Comando por defecto modificado -----------------------------------
# Ejecuta Apache en primer plano
CMD apache2-foreground