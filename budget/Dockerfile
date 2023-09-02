FROM ubuntu:latest
WORKDIR /budget
COPY . .
RUN apt update -y
RUN apt upgrade -y

#Install PHP
RUN apt install lsb-release ca-certificates apt-transport-https software-properties-common -y
RUN add-apt-repository ppa:ondrej/php
# RUN apt install php -y
# RUN apt install php8.1-dom
# RUN apt install php8.1-curl
# RUN apt install php8.1-mysql

#Install Composer
RUN curl -sS https://getcomposer.org/installer -o composer-setup.php
RUN php composer-setup.php --install-dir=/usr/local/bin --filename=composer
RUN composer clear-cache; composer update; composer install

#Install node
RUN apt install npm -y
RUN npm i -g n; n lts; npm i;

#Install MySQL
RUN apt install mysql-server -y

#Install Redis
RUN apt install redis

# RUN npm i
# RUN composer install
EXPOSE 8000