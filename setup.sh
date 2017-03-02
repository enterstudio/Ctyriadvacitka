#!/usr/bin/env bash

echo [1] dnf
echo [2] yum
echo [3] apt
echo [4] none of this
echo -n "Enter your package manager [1,2,3,4]: "
read manager

#Installing ruby development is due to installing vbguest additions below
if [ "$manager" -eq 1 ]; then
	dnf install -y ruby-devel vagrant VirtualBox composer nodejs nodejs-mkdirp npm
elif [ "$manager" -eq 2 ]; then
	yum install -y ruby-devel vagrant VirtualBox composer nodejs nodejs-mkdirp npm
elif [ "$manager" -eq 3 ]; then
	apt-get -y install ruby-dev virtualbox vagrant virtualbox-dkms curl php5-cli nodejs npm
	curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
else
	echo "You have to install all dependencies manually"
fi

vagrant plugin install vagrant-vbguest
echo "Installed dependencies on host machine"

echo -n "Enter domain for Vagrant machine (e.g. ctyriadvacitka.vagrant, vagrant.localhost): "
read domain
cat << EOF | cat >> /etc/hosts
192.168.33.24 ${domain}
EOF
echo "Added domain $domain"

cp config.local.neon app/config

echo "Installed project dependencies"

vagrant up --provider virtualbox
echo "Installed guest machine dependencies"
npm start

echo "Your vagrant box is ready, you can test it on http://$domain"
