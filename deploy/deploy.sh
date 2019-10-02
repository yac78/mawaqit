#!/bin/bash
set -e

prodServer=$MAWAQIT_PROD_IP
ppServer=$MAWAQIT_PP_IP
port=$MAWAQIT_PORT

if [ $# -lt 1 ]; then
    echo "env is mandatory"
    exit;
fi

env=$1

if [ "$env" != "pp" ] && [ "$env" != "prod" ] ; then
    echo "wrong env $env"
    exit
fi

if [ $# -eq 2 ]; then
    tag=$2
fi

currentBranch=$(git branch | grep \* | cut -d ' ' -f2)

echo "current branch > $currentBranch"

git pull origin $currentBranch
git push origin $currentBranch

server=$ppServer

if [ "$env" == "prod" ]; then
    server=$prodServer
    echo -n "Are you sur you want to deploy $tag to $env ? (y/n)"
    read answer
else
    tag=$currentBranch
    answer=y
fi

if echo "$answer" | grep -iq "^y" ;then

    # If prod then create tag
    if [ "$env" == "prod" ]; then
        git tag $tag -m "new release $tag" || true
        git push origin $tag
    fi

    ssh -p $port mawaqit@$server 'bash -s' < deploy/install.sh $env $tag
fi