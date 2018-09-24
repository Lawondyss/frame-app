FROM phusion/baseimage

RUN export LANG=C.UTF-8 && \
    add-apt-repository ppa:ondrej/php && \
    apt-get update && \
    apt-get install -y libpng12-dev libjpeg-dev libpq-dev libxml2-dev netcat

RUN apt-get install -y \
            php7.1 \
            php7.1-amqp \
            php7.1-bcmath \
            php7.1-curl \
            php7.1-dev \
            php7.1-intl \
            php7.1-mbstring \
            php7.1-memcached \
            php7.1-mysql \
            php7.1-ldap \
            php7.1-redis \
            php7.1-soap \
            php7.1-sybase \
            php7.1-uuid \
            php7.1-xml \
            php7.1-xmlrpc \
            php7.1-zip

COPY php.ini /etc/php/7.1/cli

RUN update-alternatives --set php /usr/bin/php7.1

RUN apt-get clean -y && \
    apt-get autoclean -y && \
    apt-get remove -y wget curl && \
    apt-get autoremove -y && \
    rm -rf /var/lib/apt/lists/* /var/lib/log/* /tmp/* /var/tmp/*
