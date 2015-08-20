#!/bin/bash

#
# This the installation script for phpietadmin
# Nothing spectacular, only basics covered
# Could use some more error handling
#

NC='\e[0m'
RED='\e[0;31m'
BLUE='\e[34m'

# Define log functions
function log_message() {
	echo -e "${BLUE}Message: ${NC}$@"
}

function log_error() {
	>&2 echo -e "${RED}Error: ${NC}$@"
	exit 1
}

# Make sure this runs only as root!
if [ "$(id -u)" != "0" ]; then
   log_error "Please run this script as root!"
fi

mkdir -p /var/log/phpietadmin
chown -R www-data:www-data /var/log/phpietadmin

# Define vars
BASEDIR="/usr/share/phpietadmin"
DATABASE="$BASEDIR/app/config.db"
BACKUPPATH="/var/backups"
sudoers_file="/etc/sudoers.d/phpietadmin"
date=`date +%m-%d-%y`

log_message "Checking if phpietadmin is already installed..."
if [ -f $DATABASE ]; then
    log_message "$BASDIR exists. Assuming already installed. Update installation..."
    apt-get install lsb-release
    if [ $? -ne 0 ]; then
		log_error "Could not install the packages!"
    fi

    log_message "Creating database backup..."
    log_message "Copy $DATABASE to $BACKUPPATH"
    cp $DATABASE $BACKUPPATH/phpietadmindb.$date.bak
    if [ $? -ne 0 ]; then
		log_error "Could not copy the database! Aborting..."
    fi

    log_message "Starting database update..."
    sqlite3 $DATABASE < database.update.sql
    if [ $? -ne 0 ]; then
		log_error "Database update failed!"
    fi
    log_message "Database updated successful!"


    log_message "Updating sudoers file..."
    if [ -f $sudoers_file ]; then
        rm $sudoers_file
    fi

    echo "www-data ALL=NOPASSWD: /usr/share/phpietadmin/install/phpietadmin-cli.php, /bin/lsblk, /usr/sbin/service, /sbin/vgs, /sbin/pvs, /sbin/lvs, /bin/lsblk -rn, /usr/sbin/ietadm --op *, /sbin/lvcreate, /sbin/lvremove -f *, /sbin/lvextend, /sbin/lvreduce, /sbin/shutdown --reboot *, /sbin/shutdown --poweroff *" > $sudoers_file

    log_message "Starting file update..."
    cd ..
    cp -r * $BASEDIR

    # Delete installation files
    if [ -d $BASEDIR/app/install ]; then
        rm -r $BASEDIR/app/install
    fi

    if [ -d $BASEDIR/install ]; then
        rm -r $BASEDIR/install
    fi

    # Configure apache
    if [ -f "/etc/apache2/sites-enabled/phpietadmin" ]; then
        # Wheezy
        rm "/etc/apache2/sites-enabled/phpietadmin"
        mv phpietadmin /etc/apache2/sites-enabled/
    elif [ -f "/etc/apache2/sites-enabled/phpietadmin.conf" ]; then
        # Jessie
        rm "/etc/apache2/sites-enabled/phpietadmin.conf"
        mv phpietadmin /etc/apache2/sites-enabled/phpietadmin.conf
    fi

    # restart apache
    service apache2 restart

    # Set permissions for the iet config files and phpietadmin dir
    chown -R www-data:www-data $BASEDIR
    chown -R www-data:www-data $DATABASE
    chmod 660 $DATABASE

    log_message "Update complete"
else
    log_message "Phpietadmin is not installed. Starting..."
    log_message "Installing all necessary packages..."
    apt-get update
    apt-get install -y build-essential iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-$(uname -r) sqlite3 php5-sqlite lsb-release lvm2
    if [ $? -ne 0 ]; then
		log_error "Could not install the packages!"
    fi

    # Create sudoers file
    echo "www-data ALL=NOPASSWD: /usr/sbin/service *, /sbin/vgs, /sbin/pvs, /sbin/lvs, /bin/lsblk -rn, /usr/sbin/ietadm --op *, /sbin/lvcreate, /sbin/lvremove -f *, /sbin/lvextend, /sbin/lvreduce, /sbin/shutdown --reboot *, /sbin/shutdown --poweroff *" > $sudoers_file

    # Set permissions for the iet config files and phpietadmin dir
    chown -R www-data:www-data $BASEDIR
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
        rm "/etc/apache2/sites-enabled/000-default"
        mv phpietadmin /etc/apache2/sites-enabled/
    elif [ -f "/etc/apache2/sites-enabled/000-default.conf" ]; then
        # Jessie
        rm "/etc/apache2/sites-enabled/000-default.conf"
        mv phpietadmin /etc/apache2/sites-enabled/phpietadmin.conf
    fi

    # Restart services
    service iscsitarget restart
    service apache2 restart

    # Create database and change permissions
    sqlite3 $DATABASE < database.new.sql
    chown -R www-data:www-data $DATABASE
    chmod 660 $DATABASE

    # Get password
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
    sqlite3 $DATABASE "INSERT INTO user (username, password) values (\"admin\", \"$hash\");"

     Delete installation files
    if [ -d $BASEDIR/app/install ]; then
        rm -r $BASEDIR/app/install
    fi

    if [ -d $BASEDIR/install ]; then
        rm -r $BASEDIR/install
    fi

    log_message "Installation complete"
fi