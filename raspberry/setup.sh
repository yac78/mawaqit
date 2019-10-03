#!/bin/bash
set -e

# add hosts
echo "127.0.0.1       mawaqit.local" >> /etc/hosts

# fstab
echo "tmpfs /tmp tmpfs defaults,noatime,nosuid,size=100m 0 0" >> /etc/fstab
echo "tmpfs /home/pi/mawaqit/var/cache tmpfs defaults,noatime,nosuid,size=50m 0 0" >> /etc/fstab
echo "tmpfs /home/pi/mawaqit/var/logs tmpfs defaults,noatime,nosuid,size=50m 0 0" >> /etc/fstab
echo "tmpfs /var/tmp tmpfs defaults,noatime,nosuid,size=50m 0 0" >> /etc/fstab
echo "tmpfs /var/log tmpfs defaults,noatime,nosuid,mode=0755,size=50m 0 0" >> /etc/fstab

# config autostart
echo "@sh /home/pi/mawaqit/raspberry/run.sh" >> /home/pi/.config/lxsession/LXDE-pi/autostart
echo "@xset s 0 0" >> /home/pi/.config/lxsession/LXDE-pi/autostart
echo "@xset s noblank" >> /home/pi/.config/lxsession/LXDE-pi/autostart
echo "@xset s noexpose" >> /home/pi/.config/lxsession/LXDE-pi/autostart
echo "@xset dpms 0 0 0" >> /home/pi/.config/lxsession/LXDE-pi/autostart

# update config.txt
echo "############### mawaqit conf  ################" >> /boot/config.txt
echo "hdmi_force_hotplug=1" >> /boot/config.txt
echo "disable_overscan=1" >> /boot/config.txt
echo "hdmi_group=1" >> /boot/config.txt
echo "hdmi_mode=16" >> /boot/config.txt
echo "overscan_left=30" >> /boot/config.txt
echo "overscan_right=30" >> /boot/config.txt
echo "overscan_top=20" >> /boot/config.txt
echo "overscan_bottom=20" >> /boot/config.txt
echo "dtoverlay=i2c-rtc,ds3231" >> /boot/config.txt

# install docker and other packages
apt-get update && \
apt-get install -y \
vim \
git \
wget

#unclutter \

# install docker
wget https://download.docker.com/linux/debian/dists/buster/pool/stable/armhf/containerd.io_1.2.6-3_armhf.deb
wget https://download.docker.com/linux/debian/dists/buster/pool/stable/armhf/docker-ce-cli_18.09.7~3-0~debian-buster_armhf.deb
wget https://download.docker.com/linux/debian/dists/buster/pool/stable/armhf/docker-ce_18.09.7~3-0~debian-buster_armhf.deb

dpkg -i containerd.io_1.2.6-3_armhf.deb
dpkg -i docker-ce-cli_18.09.7~3-0~debian-buster_armhf.deb
dpkg -i docker-ce_18.09.7~3-0~debian-buster_armhf.deb
sudo usermod pi -aG docker

# install docker-compose
sudo apt-get install -y python python-pip
sudo pip install docker-compose

# install teamviewer
cd /tmp
wget http://download.teamviewer.com/download/linux/teamviewer-host_armhf.deb
sudo dpkg -i teamviewer-host_armhf.deb

# enable RTC 
cp raspberry/hwclock-set /lib/udev/hwclock-set

# create files on Desktop
cp raspberry/Desktop/* /home/pi/Desktop

# install project
mkdir /home/pi/mawaqit
cd /home/pi/mawaqit
git clone https://github.com/ibrahim-zehhaf/mawaqit.git .
mkdir web/upload
chmod 777 web/upload
#app/config/parameters.yml
bin/console assetic:dump --env=prod --no-debug
bin/console d:m:ver -n --all --add
