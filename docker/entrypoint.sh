#!/bin/sh
set -e

# Render が渡す PORT を nginx 設定に反映
export PORT="${PORT:-10000}"
envsubst '$PORT' < /etc/nginx/conf.d/default.conf > /etc/nginx/conf.d/default.conf.tmp
mv /etc/nginx/conf.d/default.conf.tmp /etc/nginx/conf.d/default.conf

# php-fpm をバックグラウンド起動
php-fpm -D

# nginx をフォアグラウンド起動（←これが超重要）
nginx -g 'daemon off;'
