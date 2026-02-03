FROM php:7.4-cli

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev libonig-dev libxml2-dev zip unzip git curl libsqlite3-dev sqlite3 \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql pdo_sqlite mbstring exif pcntl bcmath gd

# Set working directory
WORKDIR /var/www/html

# Copy application
COPY . .

# Create storage directories and set permissions
RUN mkdir -p storage/framework/sessions storage/framework/views storage/framework/cache \
    && chmod -R 777 storage bootstrap/cache

# Expose port
EXPOSE 8080

# Start Laravel's built-in server (this is what works!)
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8080"]
