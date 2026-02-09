FROM php:8.3-fpm-bookworm


# --- build front assets (Vite) ---
RUN apt-get update \
  && apt-get install -y --no-install-recommends nodejs npm \
  && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/html
RUN npm ci

WORKDIR /var/www/html/src
RUN npm run build
# --- end ---
