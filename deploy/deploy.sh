#!/bin/bash

# Exit on first error
set -e

if [ $# -lt 1 ]; then
    echo "env is mandatory"
    exit;
fi

env=$1
branch=$(git branch | grep \* | cut -d ' ' -f2)

git pull origin master
git push origin $branch

server="137.74.45.69"
port="1983"

ssh -p $port mawaqit@$server 'bash -s' < deploy/install.sh $env $branch