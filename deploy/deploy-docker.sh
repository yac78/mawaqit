#!/bin/bash
set -e

prodServer="51.77.203.203"
ppServer="137.74.45.69"
port="1983"

if [ $# -lt 2 ]; then
    echo "env and tag are mandatory"
    exit
fi

env=$1
tag=$2

if [ "$env" != "pp" ] && [ "$env" != "prod" ] ; then
    echo "wrong env $env"
    exit
fi

currentBranch=$(git branch | grep \* | cut -d ' ' -f2)

echo "current branch > $currentBranch"

git pull origin $currentBranch
git push origin $currentBranch

server=$prodServer

if [ "$env" == "prod" ]; then
    echo -n "Are you sur you want to deploy $tag to $env (y/n)? "
    read answer
else
    server=$ppServer
    answer=y
fi

if echo "$answer" | grep -iq "^y" ;then
    git tag $tag -m "new release $tag" || true
    git push origin $tag
    ssh -p $port mawaqit@$server 'bash -s' < deploy/install-docker.sh $env $tag
fi