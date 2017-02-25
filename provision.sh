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
dnf install -y mysql-community-server

systemctl enable mysqld
systemctl start mysqld

CURRENT="$(grep 'temporary password' /var/log/mysqld.log | awk '{print $11}')"
echo "Current password is $CURRENT"

cat << EOF | mysql -uroot --connect-expired-password -p"$CURRENT" #log as root
ALTER USER 'root'@'localhost' IDENTIFIED BY "Lamp_001"; #change pass
quit
EOF