FROM php:8.1-cli-alpine

RUN apk update \
    && apk add --no-cache curl g++ make git autoconf bash

RUN docker-php-ext-install mysqli bcmath \
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer