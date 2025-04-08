FROM php:8.1-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install PHP extensions
RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev libxml2-dev unzip git zip \
    && docker-php-ext-install intl pdo pdo_mysql mysqli zip


# install packages
RUN apt-get update -y && \
  apt-get install -y --no-install-recommends \
  less vim wget unzip rsync git default-mysql-client libzip-dev ssl-cert \
  libcurl4-openssl-dev libfreetype6 libjpeg62-turbo libpng-dev libjpeg-dev libxml2-dev libxpm4 nano imagemagick && \
  apt-get clean && \
  apt-get autoremove -y && \
  rm -rf /var/lib/apt/lists/* && \
  echo "export TERM=xterm" >> /root/.bashrc

# install php extensions
RUN docker-php-ext-configure gd --with-jpeg && \
  docker-php-ext-install -j$(nproc) zip bcmath soap pdo_mysql gd

# Set proper Apache directory access
RUN echo "<Directory /var/www/html/>" > /etc/apache2/conf-available/oxid.conf && \
    echo "    Options Indexes FollowSymLinks" >> /etc/apache2/conf-available/oxid.conf && \
    echo "    AllowOverride All" >> /etc/apache2/conf-available/oxid.conf && \
    echo "    Require all granted" >> /etc/apache2/conf-available/oxid.conf && \
    echo "</Directory>" >> /etc/apache2/conf-available/oxid.conf && \
    a2enconf oxid.conf


WORKDIR /var/www/html
