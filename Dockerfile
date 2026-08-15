# 1. بيئة PHP الأساسية مع Apache
FROM php:8.2-apache

# 2. تثبيت الحزم الأساسية، Node.js 20 (لبناء Vue/Vite)، وPython3 للذكاء الاصطناعي
RUN apt-get update && apt-get install -y \
    git zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev \
    python3 python3-pip python3-venv \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# 3. إعداد خادم Apache ليتوجه إلى مجلد public وتفعيل إعادة التوجيه Mod_Rewrite
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN a2enmod rewrite

# 4. تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. تحديد مجلد العمل ونسخ كامل كود المشروع
WORKDIR /var/www/html
COPY . .

# 6. تثبيت مكتبات Python الخاصة بسكربتات الذكاء الاصطناعي الثمانية
RUN pip3 install --no-cache-dir --break-system-packages -r requirements.txt

# 7. تثبيت حزم PHP وتنظيف الاعتمادات
RUN composer install --no-dev --optimize-autoloader

# 8. تثبيت حزم Node.js وبناء الواجهة الأمامية Vue 3 / Vite داخل مجلد musaef-frontend
RUN cd musaef-frontend && npm install && npm run build

# 9. ضبط صلاحيات مجلدات التخزين والتخزين المؤقت
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 10. إتاحة المنفذ وتشغيل الخادم
EXPOSE 80
CMD ["sh", "-c", "php artisan config:cache && php artisan route:cache && php artisan view:cache && apache2-foreground"]
