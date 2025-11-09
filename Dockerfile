# Stage 1: Build PHP-FPM image
FROM php:8.2-fpm-alpine as php_builder

# Install PHP extensions if needed (e.g., pdo_mysql, mysqli)
# RUN docker-php-ext-install pdo_mysql mysqli

# Stage 2: Final image with Nginx and PHP-FPM
FROM alpine:latest

# Install Nginx and PHP-FPM dependencies
RUN apk add --no-cache nginx php82-fpm php82-mysqli php82-pdo_mysql

# Copy PHP-FPM configuration
# Adjust this path if your php-fpm.d is elsewhere in the php_builder stage
COPY --from=php_builder /usr/local/etc/php-fpm.d/www.conf /etc/php82/php-fpm.d/www.conf

# Copy Nginx configuration
COPY nginx.conf /etc/nginx/nginx.conf

# Remove default Nginx configuration
RUN rm -rf /etc/nginx/http.d/*

# Copy application files
COPY . /var/www/html

# Set permissions
RUN chown -R nginx:nginx /var/www/html \
    && chmod -R 755 /var/www/html \
    && chmod +x /var/www/html/run.sh # Ensure run.sh is executable

# Expose port 8080
EXPOSE 8080

# Start Nginx and PHP-FPM
CMD ["sh", "-c", "php-fpm82 --nodaemon & nginx -g \"daemon off;\""]
