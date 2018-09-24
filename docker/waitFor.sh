#!/usr/bin/env bash

### use: waitFor.sh host:port

cmdname=$(basename $0)

hostPort=(${1//:/ })
HOST=${hostPort[0]}
PORT=${hostPort[1]}

# check to see if timeout is from busybox?
TIMEOUT_PATH=$(realpath $(which timeout))
if [[ $TIMEOUT_PATH =~ "busybox" ]]; then
  ISBUSY=1
else
  ISBUSY=0
fi

start_ts=$(date +%s)

while :
do
  if [[ $ISBUSY -eq 1 ]]; then
    nc -z $HOST $PORT
    result=$?
  else
    (echo > /dev/tcp/$HOST/$PORT) >/dev/null 2>&1
    result=$?
  fi
  if [[ $result -eq 0 ]]; then
      end_ts=$(date +%s)
      echo "$cmdname: $HOST:$PORT is available after $((end_ts - start_ts)) seconds"
      break
  fi
  sleep 1
done
