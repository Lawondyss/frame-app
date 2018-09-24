#!/bin/bash

# Parameters of application
IP="128.0.0.1"
HOST="frame.local"

# Assign an address to a network interface
if ! ifconfig lo0 |grep -q $IP ; then
  sudo ifconfig lo0 alias $IP up
fi

# Add address to hosts file
if ! [ -n "$(grep $HOST /etc/hosts)" ] ; then
  HOSTS_LINE="$IP\t$HOST"
  sudo -- sh -c -e "echo '$HOSTS_LINE' >> /etc/hosts";
fi

# Run docker-compose (with arguments from CLI)
docker-compose up $*
