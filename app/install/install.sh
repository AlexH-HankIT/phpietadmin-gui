#!/bin/bash

#
# This the installation script for phpietadmin
# Nothing spectacular, only basics covered
# Could use some more error handling
# Apache configuration will probably only work on apache2.2
#

if [ "$(id -u)" != "0" ]; then
   echo "Please run this script as root!" 1>&2
   exit 1
fi

# Install bins
apt-get update
apt-get install -y build-essential iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-3.2.0-4-amd64 sqlite3 php5-sqlite

# Create initiator deny file
touch /etc/iet/initiators.deny

# If the deny file exists and is empty, it will allow all initiators
# We deny all, and allow them via the initiators.allow file
echo "ALL ALL" >> /etc/iet/initiators.deny

# Create sudoers file
sudoers_file = "/etc/sudoers.d/phpietadmin"
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
sed -i 's/None/all/g' /etc/apache2/sites-enabled/000-default
echo "Alias /phpietadmin /usr/share/phpietadmin/public/" >> /etc/apache2/sites-enabled/000-default

# Restart services
service iscsitarget restart
service apache2 restart

# Create database and change permissions
sqlite3 ../config.db < database.sql
chown -R www-data:www-data ../config.db
chmod 660 ../config.db

# Read password from database
while true
do
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