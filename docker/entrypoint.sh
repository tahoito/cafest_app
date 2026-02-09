#!/bin/sh
set -e

cd /var/www/html/src

# 書き込みできないと死ぬので保険（sh互換）
mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs

php artisan optimize:clear || true
php artisan package:discover --ansi || true

export PORT="${PORT:-10000}"
envsubst '$PORT' < /etc/nginx/conf.d/default.conf > /etc/nginx/conf.d/default.conf.tmp
mv /etc/nginx/conf.d/default.conf.tmp /etc/nginx/conf.d/default.conf

# DBが遅いと死ぬので保険
php artisan migrate --force || true

php artisan optimize:clear || true

php-fpm -D
nginx -g 'daemon off;'
