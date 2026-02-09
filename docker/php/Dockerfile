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

# app
COPY . .

# install php deps (Laravel本体は /laravel 配下)
WORKDIR /var/www/html/laravel
RUN composer install --no-dev --optimize-autoloader --no-interaction

# nginx config template (RenderのPORTを埋め込む)
RUN rm -f /etc/nginx/sites-enabled/default /etc/nginx/conf.d/default.conf || true \
  && mkdir -p /etc/nginx/conf.d

# ★テンプレの場所：いま君は docker/nginx/default.conf.template を持ってる
COPY docker/nginx/default.conf.template /etc/nginx/conf.d/default.conf.template

# ensure permissions (Laravel)
RUN chown -R www-data:www-data /var/www/html/laravel \
  && chmod -R 775 /var/www/html/laravel/storage /var/www/html/laravel/bootstrap/cache || true

EXPOSE 8080

CMD sh -c "envsubst '\$PORT' < /etc/nginx/conf.d/default.conf.template > /etc/nginx/conf.d/default.conf && php-fpm -D && nginx -g 'daemon off;'"
