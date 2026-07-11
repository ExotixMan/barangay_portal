FROM php:8.2-cli

# Install system packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    unzip \
    python3 \
    python3-pip \
    python3-venv \
    build-essential \
    libzip-dev \
    libpq-dev \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install \
    pdo_mysql \
    pdo_pgsql \
    pgsql \
    zip

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /app

# Create an isolated Python environment
RUN python3 -m venv /opt/venv

# Use the virtual environment's Python and pip by default
ENV PATH="/opt/venv/bin:$PATH"

# Copy application
COPY . .

# Install Laravel dependencies
RUN composer install \
    --no-dev \
    --prefer-dist \
    --no-interaction \
    --optimize-autoloader

# Install Python dependencies when requirements.txt exists
RUN if [ -f requirements.txt ]; then \
        python -m pip install --upgrade pip setuptools wheel && \
        python -m pip install --no-cache-dir -r requirements.txt; \
    fi

EXPOSE 10000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-10000}"]