FROM php:8.2-apache

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    python3 \
    python3-pip \
    python3-venv \
    build-essential \
    libzip-dev \
    libpq-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    libxml2-dev \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    postgresql-client \
    libreoffice-writer \
    libreoffice-core \
    libreoffice-common \
    fonts-dejavu-core \
    fonts-liberation \
    fontconfig \
    && rm -rf /var/lib/apt/lists/*

ENV LIBREOFFICE_BINARY=/usr/bin/soffice

RUN command -v soffice \
    && soffice --headless --version

RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j"$(nproc)" \
        curl \
        dom \
        gd \
        mbstring \
        xml \
        pdo_mysql \
        pdo_pgsql \
        pgsql \
        zip \
    && a2enmod rewrite headers expires

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --no-scripts \
    --optimize-autoloader

COPY . .

RUN composer dump-autoload \
        --no-dev \
        --optimize \
        --no-interaction \
        --no-scripts \
    && php artisan package:discover --ansi \
    && python3 -m venv /opt/venv \
    && /opt/venv/bin/pip install --upgrade pip setuptools wheel \
    && if [ -f requirements.txt ]; then \
        /opt/venv/bin/pip install --no-cache-dir -r requirements.txt; \
       fi \
    && sed -ri \
        -e 's!/var/www/html!/var/www/html/public!g' \
        /etc/apache2/sites-available/*.conf \
    && printf '<Directory /var/www/html/public>\n    Options FollowSymLinks\n    AllowOverride All\n    Require all granted\n</Directory>\n' \
        > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel \
    && mkdir -p \
        storage/logs \
        storage/framework/cache \
        storage/framework/sessions \
        storage/framework/views \
        storage/app/private \
        storage/app/generated \
        storage/app/backups \
    && chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R ug+rwX storage bootstrap/cache

ENV PATH="/opt/venv/bin:$PATH"
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public

EXPOSE 10000

CMD ["sh", "-c", "if [ -z \"${APP_KEY:-}\" ] || echo \"$APP_KEY\" | grep -q 'PASTE_NEW_GENERATED_APP_KEY_HERE'; then echo 'ERROR: Set a real persistent APP_KEY in the deployment environment.' >&2; exit 1; fi; sed -i \"s/^Listen .*/Listen ${PORT:-10000}/\" /etc/apache2/ports.conf; sed -i \"s/<VirtualHost \\*:[0-9]*>/<VirtualHost *:${PORT:-10000}>/\" /etc/apache2/sites-available/000-default.conf; if [ \"${RUN_MIGRATIONS:-true}\" = \"true\" ]; then php artisan migrate --force; fi; php artisan optimize:clear; exec apache2-foreground"]