#!/bin/bash

set -e

apt update
apt update
apt upgrade
apt dist-upgrade
apt install rsync git acl

# install Docker CE
apt apt-transport-https ca-certificates curl gnupg2 software-properties-common
curl -fsSL https://download.docker.com/linux/debian/gpg | sudo apt-key add -
add-repository "deb [arch=amd64] https://download.docker.com/linux/debian $(lsb_release -cs) stable"
apt update
apt install docker-ce

# install docker-compose
curl -L "https://github.com/docker/compose/releases/download/1.23.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose

# Secure server
echo "PermitRootLogin no" >> /etc/ssh/sshd_config
echo "PasswordAuthentication no" >> /etc/ssh/sshd_config
echo "AddressFamily inet" >> /etc/ssh/sshd_config

# Customise .bashrc
echo "cd /var/www/mawaqit" >> ~/.bashrc
echo "alias dock=\"docker exec -it --user $(id -u):$(id -g)\"" >> ~/.bashrc