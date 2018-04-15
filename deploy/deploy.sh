#!/bin/bash

# Exit on first error
set -e

if [ $# -lt 1 ]; then
    echo "env is mandatory"
    exit;
fi

env=$1
tag="master"

if [ "$env" != "pp" ] && [ "$env" != "prod" ] ; then
    echo "wrong env $env"
fi

if [ "$env" == "prod" ]; then
    if [ $# -lt 2 ]; then
        echo "tag is mandatory"
        exit;
    fi
    tag=$2
fi

echo -n "Are you sur you want to deploy $tag to $env (y/n)? "
read answer

if echo "$answer" | grep -iq "^y" ;then
    server="137.74.45.69"
    port="1983"

    git push

    if [ "$tag" != "master" ]; then
        git tag $tag -m "new release $2" || true
        git push origin $tag
    fi

    ssh -p $port mawaqit@$server 'bash -s' < deploy/install.sh $env $tag
fi