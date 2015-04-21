#!/bin/bash

# Create database and change permissions
sqlite3 ../config.db < database.sql
chown -R www-data:www-data ../config.db
chmod 660 ../config.db

# Read password from database
read -s -p "Enter password for user admin: " pass

# Write password to database
sqlite3 ../config.db "INSERT INTO user (username, password) values (\"admin\", \"$pass\");"

echo
echo "Done!"