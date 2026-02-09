#!/bin/sh
set -e

cd /var/www/html/src

mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs

php artisan optimize:clear || true
php artisan package:discover --ansi || true

# DBが遅い/落ちてる時に起動できなくなるの防ぐ
php artisan migrate --force || true

export PORT="${PORT:-10000}"
envsubst '$PORT' < /etc/nginx/conf.d/default.conf > /etc/nginx/conf.d/default.conf.tmp
mv /etc/nginx/conf.d/default.conf.tmp /etc/nginx/conf.d/default.conf

php-fpm -D
nginx -g 'daemon off;'
