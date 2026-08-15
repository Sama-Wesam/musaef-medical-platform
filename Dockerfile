# 1. بيئة PHP الأساسية مع Apache
FROM php:8.2-apache

# 2. تثبيت الحزم الأساسية، Node.js 22، وPython3
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    python3 python3-pip python3-venv \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_22.x | bash - \
    && apt-get install -y nodejs \
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

# 8. تقييد استهلاك ذاكرة Node.js وبناء الواجهة
ENV NODE_OPTIONS="--max-old-space-size=400"
RUN cd musaef-frontend && npm install --include=optional && npm run build

# 9. ضبط الصلاحيات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. إتاحة المنفذ وتشغيل الخادم
EXPOSE 80
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground"]
