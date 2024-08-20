# Base image
FROM webdevops/php-nginx:8.3

# Copy nginx config
# COPY docker/web/nginx/default.conf /opt/docker/etc/nginx/vhost.conf

# Update packages list
RUN apt-get update

# Install platform packages
RUN apt-get install -y apt-utils

# Install nodejs and npm
RUN curl -sL https://deb.nodesource.com/setup_18.x | bash - && \
    apt-get install -y nodejs

# Set web document root
ENV WEB_DOCUMENT_ROOT=/app/public

# Set working directory and copy files
WORKDIR /app
COPY . /app

# Set app env
ENV APP_ENV=dev

# Set composer permissions
# https://getcomposer.org/doc/03-cli.md#composer-allow-superuser
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install dependencies
RUN composer install --no-suggest --no-interaction --prefer-dist --optimize-autoloader --classmap-authoritative

RUN npm install && \
    npm run dev