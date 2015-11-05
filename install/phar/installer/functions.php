<?php
/**
 * @dir - Directory to destroy
 * @virtual[optional]- whether a virtual directory
 * @link http://webdeveloperplus.com/php/21-really-useful-handy-php-code-snippets/
 */
function destroyDir($dir, $virtual = false) {
    $ds = DIRECTORY_SEPARATOR;
    $dir = $virtual ? realpath($dir) : $dir;
    $dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
    if (is_dir($dir) && $handle = opendir($dir))  {
        while ($file = readdir($handle))  {
            if ($file == '.' || $file == '..')  {
                continue;
            } else if (is_dir($dir.$ds.$file))  {
                $this->destroy_dir($dir.$ds.$file);
            } else  {
                unlink($dir.$ds.$file);
            }
        }
        closedir($handle);
        rmdir($dir);
        return true;
    } else  {
        return false;
    }
}

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
    global $config, $baseDir;
    try {
        $config['dbBackup'] = '/var/backups/phpietadminBackup' . time() . '.tar';
        $phar = new PharData($config['dbBackup']);
        $phar->buildFromDirectory($baseDir);
        $config['phpietadminDbBackup'] = '/var/backups/phpietadminDatabaseBackup' . time();
        if(!rename($config['databaseFile'], $config['phpietadminDbBackup'])) {
            throw new exception("Can't move the database to backup location!\n");
        }
    } catch (Exception $e) {
        showErrorMessage("Can't create backup.\n" . $e->getMessage());
    }
}

function restoreDatabaseBackup() {
    global $config;
    try {
        if (file_exists($config['phpietadminDbBackup'])) {
            if(!rename($config['phpietadminDbBackup'], $config['databaseFile'])) {
                throw new exception("Can't restore the database backup!\n");
            };
        } else {
            throw new exception("Can't find the database backup!\n");
        }
    } catch (Exception $e) {
        showErrorMessage($e->getMessage());
    }
}

function removeInstallation() {
    /*
     * ToDo: Remove other directories
     */

    global $baseDir;
    try {
        if(!destroyDir($baseDir)) {
            throw new exception("Can't delete the directory " . $baseDir . "\n");
        };
    } catch (Exception $e) {
        showErrorMessage($e->getMessage());
    }
}

function updateDatabase() {
    global $config;
    try {
        if (file_exists($config['databaseScriptUpdate'])) {
            exec("sqlite3 " . $config['databaseFile'] . "< " . $config['databaseScriptUpdate'], $output, $return);

            if ($return !== 0) {
                throw new exception("Can't update the database");
            }
        } else {
            throw new exception("Can't find the database update file!");
        }
    } catch (Exception $e) {
        showErrorMessage($e->getMessage());
    }
}

function updateInstallation() {
    backupInstallation();
    restoreDatabaseBackup();
    updateDatabase();
}

function checkEnvironment() {
    global $config, $baseDir;

    echo "Hello, i'm the phpietadmin installer for version 0.6.1\n";
    echo "Warning: This program installs packages, edits config files and restarts services, but nothing serious is done without asking ;-)\n";

    while(true) {
        if (file_exists($config['databaseFile'])) {
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
        showErrorMessage("The phpietadmin directory (" . $baseDir . ") was not found!");
    } else if (!file_exists($config['databaseScriptNew'])) {
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
