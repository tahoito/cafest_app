FROM php:8.3-cli-bookworm

WORKDIR /var/www/html

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    git unzip libzip-dev libpq-dev \
  && docker-php-ext-install pdo_pgsql pgsql zip \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# アプリをコピー
COPY . .

# 依存インストール（本番用）
RUN composer install --no-dev --optimize-autoloader --no-interaction

# キャッシュ系（失敗しても止めない）
RUN php artisan config:clear || true \
 && php artisan route:clear || true \
 && php artisan view:clear || true

# RenderのPORTでHTTPを開く（ここが超重要）
CMD php -S 0.0.0.0:$PORT -t public
