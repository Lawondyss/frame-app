#!/usr/bin/env bash

echo "checking host user ID"
HOST_USER_ID=`ls -n /var/www/html/docker-compose.yml | awk '{print $3}'`
HOST_GROUP_ID=`ls -n /var/www/html/docker-compose.yml | awk '{print $4}'`
if [ ! $HOST_USER_ID -eq 0 ]; then
  echo "Changing www-data ID to host user ID $HOST_USER_ID";
  DOCKER_GROUP_OLD=`getent group $HOST_GROUP_ID | cut -d: -f1`
  if [ ! -z "$DOCKER_GROUP_OLD" ]; then
    groupmod -o -g 21555 $DOCKER_GROUP_OLD
  fi
  groupmod -o -g $HOST_GROUP_ID www-data
  DOCKER_USER_OLD=`getent group $HOST_USER_ID | cut -d: -f1`
  if [ ! -z "$DOCKER_USER_OLD" ]; then
    usermod -o -u 21555 $DOCKER_USER_OLD
  fi
  usermod -o -u $HOST_USER_ID www-data
fi
