# Use official PHP image with Apache
FROM php:8.2-apache

# Install PostgreSQL extension for PHP
RUN apt-get update && apt-get install -y libpq-dev && docker-php-ext-install pgsql pdo pdo_pgsql

# Copy all project files into the Apache web root
COPY . /var/www/html/

# Expose the web port
EXPOSE 10000

# Start Apache server
CMD ["php", "-S", "0.0.0.0:10000", "-t", "/var/www/html"]
