# -*- mode: ruby -*-
# vi: set ft=ruby :

Vagrant.configure("2") do |config|

    config.vm.provider "virtualbox"
    config.vm.box = "fedora/25-cloud-base"
    config.vm.network "private_network", ip: "192.168.33.24"
    config.vm.hostname = "Ctyriadvacitka"

    config.vm.provision :shell, path: "provision.sh"

    config.vm.synced_folder ".", "/var/www", :mount_options => ["dmode=777", "fmode=666"]
    config.vm.synced_folder "/mnt/Data/web/adminer", "/var/www/adminer", :mount_options => ["dmode=777", "fmode=666"]

    config.vm.network "forwarded_port", guest: 80, host: 80
    #config.vm.network "forwarded_port", guest: 3306, host: 3307
    
    # Optional NFS. Make sure to remove other synced_folder line too
    #config.vm.synced_folder ".", "/var/www", :nfs => { :mount_options => ["dmode=777","fmode=666"] }

end
