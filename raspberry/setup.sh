#!/bin/bash
set -e

# add hosts
sudo echo "127.0.0.1       mawaqit.local" >> /etc/hosts

# fstab
sudo echo "tmpfs /tmp tmpfs defaults,noatime,nosuid,size=100m 0 0" >> /etc/fstab
sudo echo "tmpfs /home/pi/mawaqit/var/cache tmpfs defaults,noatime,nosuid,size=50m 0 0" >> /etc/fstab
sudo echo "tmpfs /home/pi/mawaqit/var/logs tmpfs defaults,noatime,nosuid,size=50m 0 0" >> /etc/fstab
sudo echo "tmpfs /var/tmp tmpfs defaults,noatime,nosuid,size=50m 0 0" >> /etc/fstab
sudo echo "tmpfs /var/log tmpfs defaults,noatime,nosuid,mode=0755,size=50m 0 0" >> /etc/fstab

# config autostart
sudo echo "@sh /home/pi/mawaqit/raspberry/run.sh" >> /etc/xdg/lxsession/LXDE-pi/autostart

# Disable screensaver
vi /etc/lightdm/lightdm.conf
# modify this section
# [SeatDefaults]
# xserver-command=X -s 0 -dpms

# install docker and other packages
apt-get update && \
apt-get install -y \
ntp \
vim \
git \
wget

#unclutter \

# install docker
wget https://download.docker.com/linux/debian/dists/buster/pool/stable/armhf/containerd.io_1.2.6-3_armhf.deb
wget https://download.docker.com/linux/debian/dists/buster/pool/stable/armhf/docker-ce-cli_18.09.7~3-0~debian-buster_armhf.deb
wget https://download.docker.com/linux/debian/dists/buster/pool/stable/armhf/docker-ce_18.09.7~3-0~debian-buster_armhf.deb

sudo dpkg -i containerd.io_1.2.6-3_armhf.deb
sudo dpkg -i docker-ce-cli_18.09.7~3-0~debian-buster_armhf.deb
sudo dpkg -i docker-ce_18.09.7~3-0~debian-buster_armhf.deb
sudo usermod pi -aG docker

# install docker-compose
sudo apt install -y python python-pip libffi-dev python-backports.ssl-match-hostname
sudo pip install docker-compose

# install teamviewer
cd /tmp
wget http://download.teamviewer.com/download/linux/teamviewer-host_armhf.deb
sudo dpkg -i teamviewer-host_armhf.deb

# enable RTC 
echo "dtoverlay=i2c-rtc,ds3231" >> /boot/config.txt
cp raspberry/hwclock-set /lib/udev/hwclock-set

# enable tvout for teamviewer
echo "enable_tvout=1" >> /boot/config.txt

# create files on Desktop
cp raspberry/Desktop/* /home/pi/Desktop

# install project
mkdir /home/pi/mawaqit
cd /home/pi/mawaqit
git clone https://github.com/ibrahim-zehhaf/mawaqit.git .
ln -s  docker-compose.raspberry.yml  docker-compose.yml
mkdir web/upload
chmod 777 web/upload
docker-compose up -d --build
