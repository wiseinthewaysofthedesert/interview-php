FROM php:7.4-cli

RUN apt-get update && apt-get install -y \
        libicu-dev \
    && docker-php-ext-install \
        intl \
    && docker-php-ext-enable \
        intl
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install mysqli


