FROM composer AS build
WORKDIR /var/www/html

COPY ./src /var/www/html

RUN cd /var/www/html/ && composer install --ignore-platform-reqs

FROM php:7.4

WORKDIR /app 

COPY --from=build /var/www/html /app

ENV TIME_ZONE="America/Bahia" 
ENV TZ="America/Bahia" 

RUN apt-get clean autoclean

RUN apt-get update 

RUN apt-get install -y zip openssl zip unzip git 
RUN apt-get update && docker-php-ext-install pdo_mysql

COPY ./.docker/entrypoint.sh /tmp    
ENTRYPOINT ["/tmp/entrypoint.sh"]

EXPOSE 8000