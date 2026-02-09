#!/bin/sh
set -e

export PORT="${PORT:-10000}"
envsubst '$PORT' < /etc/nginx/conf.d/default.conf > /etc/nginx/conf.d/default.conf.tmp
mv /etc/nginx/conf.d/default.conf.tmp /etc/nginx/conf.d/default.conf

cd /var/www/html/src

# ここ追加：本番DBにマイグレーション適用（Shellいらない）
php artisan migrate --force

# キャッシュは今は作らない（事故りやすい）
php artisan optimize:clear || true

php-fpm -D
nginx -g 'daemon off;'
