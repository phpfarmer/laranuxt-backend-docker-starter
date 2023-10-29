# Use an official PHP image as the base image
FROM php:8-fpm

# Set working directory
WORKDIR /var/www

# Install system dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    libzip-dev \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    libbz2-dev \
    libzip-dev \
    curl \
    libonig-dev \
    libpng-dev \
    zlib1g-dev \
    libicu-dev \
    g++ \
    # Install Node.js 16 and npm
    && curl -sL https://deb.nodesource.com/setup_16.x | bash - \
    && apt-get install -y nodejs npm

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Check if npm is installed and display an error message if not
RUN node -v && npm -v || (echo "Error: npm is not installed." && exit 1)

# Install PHP extensions
RUN docker-php-ext-install zip pdo_mysql mbstring exif pcntl bcmath opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-configure intl
RUN docker-php-ext-install intl

# Install PECL extensions
RUN pecl install redis xdebug
RUN docker-php-ext-enable redis xdebug

# Get the latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Expose port 9000
EXPOSE 9000

# Start PHP-FPM
CMD ["php-fpm"]
