FROM php:7.4-apache

ENV TZ="Europe/Rome"

ARG user
ARG uid

#update apache conf
COPY ./docker/php-httpd/000-default.conf /etc/apache2/sites-available/

RUN a2enmod rewrite
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y cron curl gnupg2 unzip git libpng-dev libjpeg62-turbo-dev libxml2-dev libzip-dev zip libgmp-dev \
	nodejs npm && \
    pecl install xdebug-beta tzdata && \
	docker-php-ext-enable xdebug && \
	docker-php-ext-configure gd && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip && \
    docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

#install composer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    # Make sure we're installing what we think we're installing!
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot \
    && rm -f /tmp/composer-setup.*

#add new user
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

#copy entire app as root
COPY ./ ./

#fix permission
RUN chown -R $user:www-data /var/www/  && \
chmod 700 /var/www/html

#use created user
USER $user

RUN composer install && \
    php artisan config:clear && \
    php artisan view:clear && \ 
    php artisan cache:clear && \ 
    php artisan optimize:clear && \
    php artisan migrate 
    #php artisan schedule:run

RUN npm install && npm run dev && composer dump-autoload

EXPOSE 8081
