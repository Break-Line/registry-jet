#!/bin/bash

# Execute artisan commands
php artisan migrate:fresh --seed
php artisan assign:rolesAndPermissions
php artisan key:generate
php artisan config:clear
php artisan view:clear
php artisan cache:clear
php artisan optimize:clear
composer dump-autoload

# Execute Apache in the foreground
exec /usr/sbin/apache2ctl -DFOREGROUND
