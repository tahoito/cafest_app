############################################
# 1) Frontend build (Vite) stage
############################################
FROM node:20-bookworm-slim AS nodebuilder
WORKDIR /app

# 依存だけ先に入れてキャッシュ効かせる
COPY package.json package-lock.json ./
RUN npm ci

# ソースをコピーしてビルド
COPY . .
RUN npm run build


############################################
# 2) PHP runtime stage
############################################
FROM php:8.3-fpm-bookworm

WORKDIR /var/www/html

# OS packages + PHP extensions
RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    nginx git unzip libzip-dev libpq-dev gettext-base ca-certificates \
  && docker-php-ext-install pdo_pgsql pgsql zip \
  && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# App source
COPY . .

# Laravel dependencies
WORKDIR /var/www/html/src
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Vite build output (manifestがある場所)
COPY --from=nodebuilder /app/src/public/build /var/www/html/src/public/build

# (任意) nginx設定を自前にしてるならここでCOPY
# COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# (任意) 起動コマンドはプロジェクトに合わせて
# 例: nginx + php-fpm を両方起動するentrypointを用意するのが一般的
# COPY ./docker/entrypoint.sh /entrypoint.sh
# RUN chmod +x /entrypoint.sh
# CMD ["/entrypoint.sh"]

# nginx config
COPY ./docker/nginx/default.conf /etc/nginx/conf.d/default.conf

# entrypoint
COPY ./docker/entrypoint.sh /entrypoint.sh
RUN chmod +x /entrypoint.sh

EXPOSE 10000
CMD ["/entrypoint.sh"]
