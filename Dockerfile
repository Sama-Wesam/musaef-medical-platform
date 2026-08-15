# 1. بيئة PHP الأساسية مع Apache
FROM php:8.2-apache

# 2. تثبيت الحزم الأساسية و Python3
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    python3 python3-pip python3-venv \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. إعداد خادم Apache
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 4. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. تحديد مجلد العمل ونسخ كود المشروع
WORKDIR /var/www/html
COPY . .

# 6. تثبيت مكتبات Python
RUN pip3 install --no-cache-dir --break-system-packages -r requirements.txt

# 7. تثبيت حزم PHP
RUN composer install --no-dev --optimize-autoloader

# 8. ضبط الصلاحيات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 9. إتاحة المنفذ وتشغيل الهجرة والـ Cache والخادم تلقائياً
EXPOSE 80
CMD ["sh", "-c", "php artisan migrate --force && php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground"]
