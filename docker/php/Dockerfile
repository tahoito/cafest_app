FROM php:8.3-fpm-bookworm

WORKDIR /var/www/html

RUN apt-get update \
  && apt-get install -y --no-install-recommends \
    nginx git unzip libzip-dev libpq-dev gettext-base \
  && docker-php-ext-install pdo_pgsql pgsql zip \
  && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY . .

# --- Laravel deps ---
WORKDIR /var/www/html/src
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# --- build front assets (Vite) ---
# （node/npmは先に入れる）
RUN apt-get update \
  && apt-get install -y --no-install-recommends nodejs npm \
  && rm -rf /var/lib/apt/lists/*

# package-lock.json がどこにあるかでWORKDIRを合わせる
# もし package-lock.json がルートなら /var/www/html
# もし src なら /var/www/html/src

# 今のあなたは「ルートに package-lock.json」なのでこっち：
WORKDIR /var/www/html
RUN ls -la package-lock.json package.json \
  && npm ci \
  && npm run build
