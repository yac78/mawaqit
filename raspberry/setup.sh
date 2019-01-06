#!/bin/bash

# setup raspberry env

set -e

APP_ENV=raspberry; export APP_ENV

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

# install packages
apt-get update && \
apt-get install -y \
apt-transport-https \
ca-certificates \
curl \
wget \
vim \
git \
zip \
acl \
unclutter \
mysql-server \
nginx \
imagemagick

# PHP 7.1
echo "deb http://mirrordirector.raspbian.org/raspbian/ buster main contrib non-free rpi" > /etc/apt/sources.list.d/php7-1.list
apt-get update && apt-get install -y \
php7.1 \
php7.1-fpm \
php7.1-mysql \
php7.1-curl \
php7.1-xml \
php7.1-zip \
php7.1-json \
php7.1-imagick

# update php conf
sed -i "s/; date.timezone =.*/date.timezone = Europe\/Paris/" /etc/php/7.1/fpm/php.ini
sed -i "s/; memory_limit =.*/memory_limit = 128/" /etc/php/7.1/fpm/php.ini
sed -i "s/; max_input_vars =.*/max_input_vars = 10000/" /etc/php/7.1/fpm/php.ini
sed -i "s/upload_max_filesize =.*/upload_max_filesize = 20M/" /etc/php/7.1/fpm/php.ini
sed -i 's/error_reporting = E_ALL & ~E_DEPRECATED & ~E_STRICT/error_reporting = E_ALL/g' /etc/php/7.1/fpm/php.ini
sed -i "s/display_errors = Off/display_errors = On/" /etc/php/7.1/fpm/php.ini

# install composer
curl -k -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
chmod +x /usr/local/bin/composer

# install teamviewer
cd /tmp
wget http://download.teamviewer.com/download/linux/teamviewer-host_armhf.deb
sudo dpkg -i teamviewer-host_armhf.deb

# create mariadb user mawaqit/mawaqit
mariadb
CREATE USER 'mawaqit'@'localhost';
GRANT ALL ON *.* TO 'mawaqit'@'localhost';
exit;

# install project
mkdir /home/pi/mawaqit
cd /home/pi/mawaqit
git clone https://github.com/binary010100/mawaqit.git .

# enable RTC 
cp raspberry/hwclock-set /lib/udev/hwclock-set

HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var

mkdir web/upload
chmod 777 web/upload

composer install --optimize-autoloader --no-interaction
bin/console assets:install --env=prod --no-debug

#cp app/config/parameters.yml.dist app/config/parameters.yml
sed -i "s/symfony/mawaqit/" app/config/parameters.yml
sed -i "s/root/mawaqit/" app/config/parameters.yml
bin/console c:c --env=prod

bin/console assetic:dump --env=prod --no-debug
bin/console d:d:cre
bin/console d:s:u --force
bin/console h:f:l -n
bin/console d:m:ver -n --all --add

cp raspberry/vhost /etc/nginx/sites-enabled/default
service php7.1-fpm restart
service nginx restart

# create files on Desktop
cp raspberry/Desktop/* /home/pi/Desktop
