FROM php:8.1-fpm

#WORKDIR /var/www/html

RUN apt-get update && \
    apt-get install -y \
        curl \
        gnupg \
        software-properties-common \
        git \
        unzip \
        php8.1-{dev,gd,curl,intl,mysql,mbstring,zip,xml,cli,apcu,bcmath,soap} \
        ca-certificates

RUN curl -sL https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin

RUN npm install -g yarn

COPY . /var/www/html/chamilo
RUN cp .env.docker .env

WORKDIR /var/www/html/chamilo

RUN composer install

RUN yarn set version stable && \
    yarn up

RUN touch .env && \
    chown -R www-data: /var/ .env config/

EXPOSE 80

CMD ["apache2", "-f", "-H"]