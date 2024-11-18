#!/bin/bash

cd /var/www/html/

# Execute artisan commands
php artisan migrate:fresh --seed
php artisan assign:rolesAndPermissions
php artisan key:generate
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
composer dump-autoload

apache2-foreground