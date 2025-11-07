FROM php:8.5-rc-fpm

WORKDIR /var/www/html

# 1. Zainstaluj zależności systemowe
#    - git (do pobierania ze źródła)
#    - unzip (do pobierania z 'dist')
#    - libzip-dev (potrzebne do zbudowania rozszerzenia PHP 'zip')
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev

# 2. Zainstaluj rozszerzenia PHP
#    (dodaliśmy 'zip' do listy)
RUN docker-php-ext-install pdo pdo_mysql zip

# 3. Zainstaluj Composera (bez zmian)
COPY --from=composer /usr/bin/composer /usr/bin/composer