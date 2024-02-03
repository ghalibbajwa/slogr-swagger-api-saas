FROM php:7.4-apache

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql 

RUN a2enmod headers
RUN echo '<IfModule mod_headers.c>\n' \
    '    Header set Access-Control-Allow-Origin "*"\n' \
    '    Header set Access-Control-Allow-Methods "GET, POST, PUT, DELETE, OPTIONS"\n' \
    '    Header set Access-Control-Allow-Headers "Authorization, Content-Type, X-Requested-With"\n' \
    '    Header set Access-Control-Max-Age "3600"\n' \
    '</IfModule>' > /etc/apache2/conf-available/cors.conf




RUN a2enconf cors



RUN  apt-get -y install cron

RUN a2enmod rewrite

RUN usermod -u 1000 www-data


# Enable Apache SSL configuration
COPY cert.crt  /etc/ssl/certs/ssl-cert-snakeoil.pem
COPY private.key  /etc/ssl/private/ssl-cert-snakeoil.key



RUN a2enmod ssl
RUN a2ensite default-ssl


ENV APACHE_DOCUMENT_ROOT /var/www/html/

RUN a2enmod rewrite

RUN docker-php-ext-install bcmath
RUN docker-php-ext-enable bcmath

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
EXPOSE 443

RUN crontab -l | { cat; echo "*/2 * * * *   cd /var/www/html && /usr/local/bin/php artisan schedule:run >> /var/log/cron.log 2>&1"; } | crontab -

RUN touch /var/log/cron.log
RUN chmod 666 /var/log/cron.log
RUN chown -R www-data:www-data /var/log/cron.log




CMD cron && docker-php-entrypoint apache2-foreground