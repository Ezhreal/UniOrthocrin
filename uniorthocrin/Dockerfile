FROM php:8.2-cli

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    gnupg \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libwebp-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg --with-webp \
    && docker-php-ext-install pdo_mysql gd bcmath zip pcntl ftp \
    && rm -rf /var/lib/apt/lists/*

# Node.js for Vite hot reload
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get update && apt-get install -y nodejs \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Aumentar limites do PHP para suportar upload de chunks (chunk size = 5MB no JS)
RUN echo "upload_max_filesize=10M\npost_max_size=12M\nmemory_limit=256M\nmax_execution_time=300" \
    > /usr/local/etc/php/conf.d/uploads.ini

WORKDIR /var/www/html

CMD ["php", "-v"]
