FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

RUN  apt-get -y install cron


RUN usermod -u 1000 www-data
# Copy files to container
COPY . /var/www/html/




RUN chown -R www-data:www-data  /var/www/html/storage \
    && chmod -Rf 0777 /var/www/html/storage 

RUN chmod -R 777 storage/
# Set environment variables
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Expose port
EXPOSE 80

RUN crontab -l | { cat; echo "*/2 * * * *   cd /var/www/html && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1"; } | crontab -
RUN touch /var/log/cron.log
RUN chmod 666 /var/log/cron.log
RUN chown -R www-data:www-data /var/log/cron.log




CMD cron && docker-php-entrypoint apache2-foreground