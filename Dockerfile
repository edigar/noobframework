FROM php:5.6-apache
LABEL maintainer 'Edigar Herculano <edigarhdev@gmail.com>'

COPY ./ /var/www/html

RUN a2enmod rewrite

EXPOSE 80
