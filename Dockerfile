FROM php:8.4-apache

# Instala dependências do sistema e Node.js 20
RUN apt-get update && apt-get install -y --no-install-recommends \
    curl ca-certificates gnupg2 \
 && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
 && apt-get install -y --no-install-recommends nodejs \
 && rm -rf /var/lib/apt/lists/*

# PHP extensions
RUN docker-php-ext-install pdo pdo_mysql