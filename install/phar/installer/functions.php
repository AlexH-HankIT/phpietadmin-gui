<?php
function showInfoMessage() {

}

function showDebugMessage() {

}

function random_password( $length = 8 ) {
    $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
    $password = substr( str_shuffle( $chars ), 0, $length );
    return $password;
}

function showErrorMessage($message, $abort = true) {
    echo "\e[0;31mError:\e[0m " . $message . "\n";

    if ($abort === true) {
        die("Aborting...\n");
    }
}

function generateAuthCode() {
    global $config;
    if (file_exists($config['authFile'])) {
        echo "Code was already generated!\n";
    } else {
        $password = random_password(8);
        if (file_put_contents($config['authFile'], $password) !== false) {
            echo "Success\n";
            echo "Code is " . $password . "\n";
            chown($config['authFile'], 'www-data');
        } else {
            echo "Failure\n";
        }
    }
}

function backupInstallation() {
    global $config;
    try {
        $phar = new PharData('/var/backups/phpietadmin_backup' . time() . '.tar');
        $phar->buildFromDirectory($config['baseDir']);
    } catch (Exception $e) {
        showErrorMessage("Can't create backup.\n" . $e->getMessage());
    }
}

function removeInstallation() {

}

function extractDatabaseFromBackup() {

}

function updateDatabase() {

}

function updateInstallation() {
    backupInstallation();
    // deleting the phpietadmin folder /usr/share/phpietadmin
    // copying the new files
    // extract the database
    // update the database
    // done
}

function checkEnvironment() {
    global $config;

    echo "Hello, i'm the phpietadmin installer for version 0.6.1\n";
    echo "Warning: This program installs packages, edits config files and restarts services, but nothing serious is done without asking ;-)\n";

    while(true) {
        if (file_exists($config['baseDir'] . '/' . $config['databaseFile'])) {
            echo "2 Update existing installation\n";
            echo "3 Delete old installation and install a fresh one\n";
        } else {
            echo "1 Install phpietadmin\n";
        }

        $line = readline("What can i do for you? ");

        if ($line === "1") {
            $config['userInput']['action'] = "install";
            break;
        } else if ($line === "2") {
            $config['userInput']['action'] = "update";
            break;
        } else if ($line === "3") {
            $config['userInput']['action'] = "reinstall";
            break;
        }
    }

    if (!is_dir($config['baseDir'])) {
        showErrorMessage("The phpietadmin directory (" . $config['baseDir'] . ") was not found!");
    } else if (!file_exists($config['baseDir'] . '/' . $config['databaseScriptNew'])) {
        showErrorMessage("The phpietadmin database file (" . $config['databaseScriptNew'] . ") was not found!");
    }

    if ($config['userInput']['action'] !== "update") {
        while (true) {
            $line = readline("Configure apache2 (YES|no|help): ");
            if ($line === "yes" || empty($line)) {
                $config['userInput']['configureApache'] = true;
                break;
            } else if ($line === "no") {
                $config['userInput']['configureApache'] = false;
                break;
            } else if ($line === "help") {
                echo "This program can configure apache2 automatically. \nNo config files are deleted. But if you already use apache2 for other applications, you should do this manually. \nThe phpietadmin apache2 config file is located under /usr/share/phpietadmin/install/phpietadmin\n\n";
            }
        }

        while (true) {
            $line = readline("Configure ietd (YES|no|help): ");
            if ($line === "yes" || empty($line)) {
                $config['userInput']['configureIetd'] = true;
                break;
            } else if ($line === "no") {
                $config['userInput']['configureIetd'] = false;
                break;
            } else if($line === "help") {
                echo "This program can configure the iet daemon automatically. \nIt will delete the \'ALL ALL\' initiators allow rule, since it isn't supported by phpietadmin. Also, the ietd service will be restarted!\n\n";
            }
        }

        while (true) {
            $line = readline("Setup sudo (YES|no|help): ");
            if ($line === "yes" || empty($line)) {
                $config['userInput']['configureSudo'] = true;
                break;
            } else if ($line === "no") {
                $config['userInput']['configureSudo'] = false;
                break;
            } else if ($line === "help") {
                echo "This program can configure sudo automatically. \nThe phpietadmin sudoer file (/usr/share/phpietadmin/install/sudoer) will be copied to the sudoer include directory (/etc/sudoers.d/).\nThis is necessary, because phpietadmin uses a few shell commands. It won't work correctly without it!\n\n";
            }
        }
    }
}
