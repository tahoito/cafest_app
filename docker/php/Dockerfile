FROM php:8.3-fpm-bookworm

WORKDIR /var/www/html

# system deps + nginx
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    nginx \
    git \
    unzip \
    libzip-dev \
    libpq-dev \
    gettext-base \
  && docker-php-ext-install pdo_pgsql pgsql zip \
  && rm -rf /var/lib/apt/lists/*

# composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# app (Laravelはルート直下)
COPY . .

# composer install（Laravelのcomposer.jsonを使う）
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# nginx config template
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf || true \
  && mkdir -p /etc/nginx/conf.d

COPY docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template

# permissions（Laravel直下）
RUN mkdir -p /var/www/html/storage /var/www/html/bootstrap/cache \
  && chown -R www-data:www-data /var/www/html \
  && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

  
EXPOSE 8080

CMD sh -c "envsubst '\$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf && php-fpm -D && nginx -g 'daemon off;'"
