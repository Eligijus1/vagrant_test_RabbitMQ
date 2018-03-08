#!/usr/bin/env bash

echo "# IPv4 and IPv6 localhost aliases:" | sudo tee /etc/hosts
echo "127.0.0.1 vagrant.RabbitMQ.test  vagrant  localhost" | sudo tee -a /etc/hosts
echo "::1       vagrant.RabbitMQ.test  vagrant  localhost" | sudo tee -a /etc/hosts
echo "10.0.2.15 vagrant.RabbitMQ.test  vagrant  localhost" | sudo tee -a /etc/hosts

sudo ex +"%s@DPkg@//DPkg" -cwq /etc/apt/apt.conf.d/70debconf
sudo dpkg-reconfigure debconf -f noninteractive -p critical

# Fixing languages:
sudo apt-get install -y language-pack-en-base
sudo LC_ALL=en_US.UTF-8 add-apt-repository ppa:ondrej/php

# Update packages:
apt-get update

# Install nmap:
sudo apt-get install -y nmap

# Install MySQL:
# echo "mysql-server-5.5 mysql-server/root_password password root" | debconf-set-selections
# echo "mysql-server-5.5 mysql-server/root_password_again password root" | debconf-set-selections
# sudo apt-get -y install mysql-server-5.5

# Apache install:
apt-get install -y apache2
#apt-get install -y apache2 > null 2>&1
if ! [ -L /var/www ]; then
  rm -rf /var/www
  ln -fs /vagrant /var/www
fi

# Installing PHP 7.1 and some extra libraries:
sudo apt-get install -y php7.1
sudo apt-get install -y php7.1-xml 
sudo apt-get install -y php7.1-curl

# Add DNS to /etc/resolv.conf
echo "nameserver 8.8.8.8" | sudo tee -a /etc/resolv.conf
echo "nameserver 8.8.4.4" | sudo tee -a /etc/resolv.conf

# Install composer:
cd /tmp/
curl -sS https://getcomposer.org/installer | sudo php -- --install-dir=/usr/local/bin --filename=composer

# Install git:
sudo apt-get install -y git

# Install RabbitMQ:
sudo apt-get install rabbitmq-server -y
sudo rabbitmq-plugins enable rabbitmq_web_stomp
sudo rabbitmq-plugins enable mochiweb
sudo rabbitmq-plugins enable rabbitmq_management
sudo rabbitmq-plugins enable rabbitmq_web_stomp_examples
sudo service rabbitmq-server restart

# Install extra modules:
sudo apt-get install php7.1-mbstring -y
sudo apt-get install php7.1-bcmath -y
sudo apt-get install zip -y
sudo apt-get install unzip -y
sudo apt-get install php7.1-zip -y

# sudo a2ensite symfony3_test.vagrant.test1.dev.conf
# sudo service apache2 stop
# sudo service apache2 start


