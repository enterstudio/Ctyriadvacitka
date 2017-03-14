#!/usr/bin/env bash

USER=lamp
PASS=Lamp_001

########
#Apache#
########
dnf install -y httpd
systemctl enable httpd

cat << EOF | cat >> /etc/httpd/conf/httpd.conf
<Directory "/var/www">
    Options Indexes FollowSymLinks
    AllowOverride All
    # Allow open access:
    Require all granted
</Directory>

DocumentRoot "/var/www"

<IfModule dir_module>
    DirectoryIndex index.*
</IfModule>
EOF

chmod -R a+xr /var/www
chmod a+x /var/www

#SELinux causes 403 error and we do not need it
sed s/SELINUX=enforcing/SELINUX=disabled/ >/etc/selinux/config
setenforce 0

#####
#PHP#
#####
dnf install -y php php-common php-pecl-apcu php-cli php-pear php-pdo php-mysqlnd php-pgsql php-pecl-memcache php-pecl-memcached php-gd php-mbstring php-mcrypt php-xml php-json php-pecl-xdebug

systemctl restart httpd

#######
#MySQL#
#######
dnf install -y https://dev.mysql.com/get/mysql57-community-release-fc25-9.noarch.rpm
dnf install -y mysql-community-server wget

systemctl enable mysqld
systemctl start mysqld

CURRENT="$(grep 'temporary password' /var/log/mysqld.log | awk '{print $11}')"
echo "Current password is $CURRENT"

wget https://gist.githubusercontent.com/krouma/9f1c01ae144ed514869bcb9bde2521bd/raw/04326e0fb1fb53df3b62303ff45186c060c8eae1/lamp.sql

cat << EOF | mysql -uroot --connect-expired-password -p"$CURRENT" #log as root
ALTER USER 'root'@'localhost' IDENTIFIED BY "Lamp_001"; #change pass
\. lamp.sql
quit
EOF

rm lamp.sql

echo "Provision completed"
