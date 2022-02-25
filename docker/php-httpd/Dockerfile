FROM php:7.4-apache

ENV TZ="Europe/Rome"

ARG user
ARG uid

#update apache conf
COPY ./docker/php-httpd/000-default.conf /etc/apache2/sites-available/

RUN a2enmod rewrite
RUN apt-get update && apt-get upgrade -y
RUN apt-get install -y cron curl gnupg2 unzip git libpng-dev libjpeg62-turbo-dev libxml2-dev libzip-dev zip libgmp-dev tzdata \
	nodejs npm && \
	docker-php-ext-configure gd && \
    docker-php-ext-configure zip && \
    docker-php-ext-install zip && \
    docker-php-ext-install pdo pdo_mysql

# Install xdebug
RUN docker-php-source extract \
    && pecl install xdebug \
    && echo "zend_extension=$(find /usr/local/lib/php/extensions/ -name xdebug.so)\n" > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "[xdebug]" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.mode=develop,debug" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.client_host=host.docker.internal" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && echo "xdebug.start_with_request=yes" >> /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    && docker-php-ext-enable xdebug \
    && docker-php-source delete

WORKDIR /var/www/html

#install composer
RUN curl -o /tmp/composer-setup.php https://getcomposer.org/installer \
    && curl -o /tmp/composer-setup.sig https://composer.github.io/installer.sig \
    # Make sure we're installing what we think we're installing!
    && php -r "if (hash('SHA384', file_get_contents('/tmp/composer-setup.php')) !== trim(file_get_contents('/tmp/composer-setup.sig'))) { unlink('/tmp/composer-setup.php'); echo 'Invalid installer' . PHP_EOL; exit(1); }" \
    && php /tmp/composer-setup.php --no-ansi --install-dir=/usr/local/bin --filename=composer --snapshot \
    && rm -rf /tmp/*

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
    #:fresh --seed

RUN npm install && \
    npm run dev && \
    composer dump-autoload

EXPOSE 8081
