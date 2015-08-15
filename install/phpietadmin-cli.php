<?php
function check_sapi($message)
{
    if (PHP_SAPI === !'cli') {
        print $message;
        exit(1);
    }
}

function check_language()
{
    $lang = shell_exec('echo $LANGUAGE');
    if ($lang = !"de_DE" or $lang = !"en_EN") {
        print "Error - this software only runs on systems with de_DE or en_EN localization!\n";
        exit(1);
    } else {
        return $lang;
    }
}

function display_help()
{
    echo "This is the phpietadmin-cli tool\n\n";
    echo "Options: \n";
    echo "--help/-h display this help \n";
    echo "--action/-a install/update phpietadmin\n";
    echo "--version/-v display version information\n";
}

// Exit if we are not called via cli
check_sapi("Call this script via cli only!");

// Check environment
check_language();

// get cli options
$options = getopt('a:hv');

if (empty($options)) {
    display_help();
} else if (isset($options['h'])) {
    display_help();
} else if (!empty($options['a'])) {
    if ($options['a'] == 'update') {
        // better way:
        // write cli tool
        // create instruction file in new version
        // cli tool executes instruction file
        // done

        // instruction file:
        // update.inc.php
        // install.inc.php

        // contain classes which inherent the logging functions from *this* application
        // contain all necessary information to update/install/reinstall phpietadmin with error logging

        // use symlink in install folder for apache2 and sudoer config

        // possible way:
        // fetch latest release from github
        // extract it to /tmp/randomstring/
        // create full backup (don't forget sudoer file
        // delete sudoer file
        // create new sudoer file
        // update database with database.update.sql
        // check for errors
        // update files
        // move database to temp location
        // delete /usr/share/phpietadmin
        // copy new files to /usr/share/phpietadmin
        // copy database
        // flush sessions table
        // delete apache config (check for wheezy or jessie)
        // restart apache2
        // update done
    } else if ($options['a'] == 'install') {

    } else if (isset($options['v'])) {
        // display version information here
    } else {
        display_help();
    }
} else {
    display_help();
}