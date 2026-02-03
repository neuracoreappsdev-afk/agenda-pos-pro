FROM php:7.4-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl libsqlite3-dev sqlite3 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Copy files to /app
WORKDIR /app
COPY . /app

# Permissions
RUN chmod -R 777 storage bootstrap/cache

# Create simple test file
RUN echo "<?php echo 'IT WORKS - ' . date('Y-m-d H:i:s');" > /app/test.php

EXPOSE 8080

# Start server from /app directory explicitly
CMD cd /app && php -S 0.0.0.0:8080
