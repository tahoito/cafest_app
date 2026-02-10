#!/bin/sh
set -e

cd /var/www/html/src

mkdir -p bootstrap/cache storage/framework/cache storage/framework/sessions storage/framework/views storage/logs

php artisan package:discover --ansi || true

# 本番だけキャッシュを生成して高速化（ローカルは従来通りクリア）
if [ "${APP_ENV}" = "production" ]; then
  php artisan config:cache --ansi || true
  php artisan event:cache --ansi || true
  php artisan route:cache --ansi || true
  php artisan view:cache --ansi || true
else
  php artisan optimize:clear || true
fi

# DBが遅い/落ちてる時に起動できなくなるの防ぐ
php artisan migrate --force || true

export PORT="${PORT:-10000}"
envsubst '$PORT' < /etc/nginx/conf.d/default.conf > /etc/nginx/conf.d/default.conf.tmp
mv /etc/nginx/conf.d/default.conf.tmp /etc/nginx/conf.d/default.conf

php-fpm -D
nginx -g 'daemon off;'
