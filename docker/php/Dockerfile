ARG PHP_VERSION
FROM php:${PHP_VERSION}-cli

# Install packages
RUN apt-get update && DEBIAN_FRONTEND=noninteractive apt-get install -yq \
        zip \
        unzip \
        libzip-dev \
        libicu-dev

# Install PCOV extension, we need this to generate phpunit's code coverage report
RUN pecl install pcov && docker-php-ext-enable pcov
RUN docker-php-ext-install bcmath
RUN docker-php-ext-install zip
RUN docker-php-ext-configure intl && docker-php-ext-install intl
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

