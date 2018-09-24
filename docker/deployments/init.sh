#!/usr/bin/env bash

set -e

: ${ROOT:=/var/www/html}
: ${CHECK_FILE:=$ROOT/.deploy_done}


rm -f $CHECK_FILE

as_www_user() {
  su -c "$1" -s /bin/bash www-data
}

/var/www/setUserID.sh

php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
php composer-setup.php --install-dir=/usr/bin --filename=composer
php -r "unlink('composer-setup.php');"

mkdir -p -m 777 /var/composer_cache

composer global require hirak/prestissimo

cd /var/www/html

echo ">> install dependencies via Composer"
as_www_user "composer install --prefer-dist"

echo ">> waiting for MariaDB"
/var/www/waitFor.sh $MARIADB_CONTAINER_NAME:3306

echo ">> run DB migration"
as_www_user "vendor/bin/phinx migrate -e $PHINX_ENVIRONMENT"

echo ">> deploy done"
date > $CHECK_FILE
