#!/usr/bin/env bash

/wait_for_it.sh mysql:3306 -t $WAIT_FOR_MYSQL_TIMEOUT
php bin/console doctrine:migrations:migrate -n --allow-no-migration
php bin/console doctrine:fixtures:load -n -e dev

php-fpm
