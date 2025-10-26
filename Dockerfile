# --- Dockerfile ---
FROM php:8.2-apache

# -----------------------------------------------------
# 🧰 Systempakete für Composer, Git & Co.
# -----------------------------------------------------
RUN apt-get update && apt-get install -y \
    git unzip zip && rm -rf /var/lib/apt/lists/*

# -----------------------------------------------------
# 🎼 Composer installieren (optional, falls du später Libs nutzt)
# -----------------------------------------------------
RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" \
 && php composer-setup.php --install-dir=/usr/local/bin --filename=composer \
 && rm composer-setup.php

# -----------------------------------------------------
# 🔧 Apache anpassen
# -----------------------------------------------------
# mod_rewrite aktivieren (z. B. für saubere URLs)
RUN a2enmod rewrite

# Webroot auf /var/www/html/src/public umstellen
ENV APACHE_DOCUMENT_ROOT /var/www/html/src/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf /etc/apache2/apache2.conf

# -----------------------------------------------------
# 📂 Arbeitsverzeichnis im Container
# -----------------------------------------------------
WORKDIR /var/www/html

# Projektdateien ins Image kopieren
COPY . /var/www/html

# -----------------------------------------------------
# 🌐 Exponierter Port (Standard: 80)
# -----------------------------------------------------
EXPOSE 80
