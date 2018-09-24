#!/bin/bash

set -e

APACHE_ENVVARS=${APACHE_CONFDIR=/etc/apache2}/envvars
APACHE_PID_FILE=${APACHE_RUN_DIR=/var/run/apache2}/apache2.pid

echo ">> install Apache"
apt-get update
apt-get install -y \
        apache2 \
        apache2-dev \
        python-setuptools \
        git-core \
        build-essential

echo ">> install RPAF module"
rm -rf /tmp/rpaf
mkdir /tmp/rpaf/ && cd /tmp/rpaf/
git clone https://github.com/gnif/mod_rpaf /tmp/rpaf/
make
make install /usr/lib/apache2/modules

cp $DOCKER_CONFDIR/php.conf $APACHE_CONFDIR/conf-available/
a2enconf php

cp $DOCKER_CONFDIR/vhost.conf $APACHE_CONFDIR/sites-available/000-default.conf
cp /var/www/php.ini /etc/php/7.0/apache2

echo 'ServerName docker.local' > $APACHE_CONFDIR/conf-available/sitename.conf
a2enconf sitename

a2enmod rewrite

a2dismod mpm_event
a2enmod mpm_prefork

echo ">> cleaning"
apt-get clean -y
apt-get autoclean -y
apt-get remove -y wget curl
apt-get autoremove -y
rm -rf /var/lib/apt/lists/* /var/lib/log/* /tmp/* /var/tmp/*

if test -f "$APACHE_ENVVARS"; then
  . "$APACHE_ENVVARS"
fi

/var/www/setUserID.sh

echo ">> clean app cache"
rm -rf $ROOT/temp/cache/*

echo ">> run Apache"
# Apache gets grumpy about PID files pre-existing
rm -f $APACHE_PID_FILE
exec apache2 -DFOREGROUND
