#!/bin/bash

#
# This the installation script for phpietadmin
# Nothing spectacular, only basics covered
# Could use some more error handling
#

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

# Define vars
BASEDIR="/usr/share/phpietadmin"
DATABASE="$BASDIR/app/config.db"
BACKUPPATH="/var/backups"
sudoers_file="/etc/sudoers.d/phpietadmin"

log_message "Checking if phpietadmin is already installed..."
if [ -d $BASEDIR ]; then
    log_message "$BASDIR exists. Assuming already installed. Update installation..."
    apt-get install lsb-release
    if [ $? -ne 0 ]; then
		log_error "Could not install the packages!"
    fi

    log_message "Creating database backup..."
    log_message "Copy $DATABASE to $BACKUPPATH"
    cp $DATABASE $$BACKUPPATH
    if [ $? -ne 0 ]; then
		log_error "Could not copy the database! Aborting..."
    fi

    log_message "Starting database update..."
    sqlite3 $DATABASE < database.update.sql
    if [ $? -ne 0 ]; then
		log_error "Database update failed!"
    fi
    log_message "Database updated successful!"
    log_message "Update complete"
else
    log_message "Phpietadmin is not installed. Starting..."
    log_message "Installing all necessary packages..."
    apt-get update
    apt-get install -y build-essential iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-$(uname -r) sqlite3 php5-sqlite lsb-release
    if [ $? -ne 0 ]; then
		log_error "Could not install the packages!"
    fi


    # Create sudoers file
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

    log_message "Installation complete"
fi