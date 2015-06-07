#!/bin/bash

#
# This the installation script for phpietadmin
# Nothing spectacular, only basics covered
# Could use some more error handling
#

function usage() {
    echo "Usage:"
    echo "bash /usr/share/phpietadmin/app/install/install.sh update"
    echo "bash /usr/share/phpietadmin/app/install/install.sh freshinstall"
}

if [ "$(id -u)" != "0" ]; then
   echo "Please run this script as root!" 1>&2
   exit 1
fi

if [ -z $1 ]; then
    usage
    exit 1
fi

if [ $1 == "freshinstall" ]; then
    # Install bins
    apt-get update
    apt-get install -y build-essential iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-3.2.0-4-amd64 sqlite3 php5-sqlite lsb-release

    # Create sudoers file
    sudoers_file="/etc/sudoers.d/phpietadmin"
    if [ -f $sudoers_file ]; then
        rm $sudoers_file
    fi

    cat > $sudoers_file << "EOF"
        www-data ALL=NOPASSWD: /usr/sbin/service iscsitarget *, /sbin/vgs, /sbin/pvs, /sbin/lvs, /bin/lsblk -rn, /usr/sbin/ietadm --op *, /sbin/lvcreate, /sbin/lvremove -f *, /sbin/lvextend, /sbin/lvreduce
EOF

    # Set permissions for the iet config files and phpietadmin dir
    chown -R www-data:www-data /usr/share/phpietadmin
    chown -R root:www-data /etc/iet
    chmod -R 770 /etc/iet

    # Enable mods
    a2enmod rewrite

    # Enable service
    sed -i 's/false/true/g' /etc/default/iscsitarget

    # Secure installation
    sed -i '/ALL ALL/d' /etc/iet/initiators.allow

    # Configure apache
    if [ -f "/etc/apache2/sites-enabled/000-default" ]; then
        # Wheezy
        sed -i 's/None/all/g' /etc/apache2/sites-enabled/000-default
        echo "Alias /phpietadmin /usr/share/phpietadmin/public/" >> /etc/apache2/sites-enabled/000-default
    elif [ -f "/etc/apache2/sites-enabled/000-default.conf" ]; then
        # Jessie
        sed -i 's/None/all/g' /etc/apache2/sites-enabled/000-default.conf
        echo "Alias /phpietadmin /usr/share/phpietadmin/public/" >> /etc/apache2/sites-enabled/000-default.conf
    fi

    # Restart services
    service iscsitarget restart
    service apache2 restart

    # Create database and change permissions
    sqlite3 ../config.db < database.new.sql
    chown -R www-data:www-data ../config.db
    chmod 660 ../config.db

    # Read password from database
    while true; do
        read -s -p "Please enter password for user admin: " password
        echo
        read -s -p "Please enter password for user admin again: " password2
        echo
        [ "$password" == "$password2" ] && break
        echo "Please try again"
    done


    # Create sha2 hash
    hash=$(echo -n $password | sha256sum | head -c 64)

    # Write password to database
    sqlite3 ../config.db "INSERT INTO user (username, password) values (\"admin\", \"$hash\");"

    echo
    echo "Done!"
elif [ $1 == "update" ]; then
    apt-get install lsb-release

    sqlite3 ../config.db < database.update.sql
else
    usage
    exit 1
fi