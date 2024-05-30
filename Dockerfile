FROM php:7.4-apache

# Install mysqli extension
RUN docker-php-ext-install mysqli

# Restart Apache to apply changes
RUN service apache2 restart
