<?php
class Base {
    protected $baseDir;
    protected $config;

    public function __construct($baseDir, $config) {
        $this->baseDir = $baseDir;
        $this->config = $config;
    }

    function removeInstallation($clean = false) {
        /*
         * ToDo: Remove other directories
         */

        try {
            if(!$this->destroyDir($this->baseDir)) {
                throw new exception("Can't delete the directory " . $this->baseDir . "\n");
            };
        } catch (Exception $e) {
            $this->showErrorMessage($e->getMessage());
        }
    }

    function destroyDir($dir, $virtual = false) {
        $ds = DIRECTORY_SEPARATOR;
        $dir = $virtual ? realpath($dir) : $dir;
        $dir = substr($dir, -1) == $ds ? substr($dir, 0, -1) : $dir;
        if (is_dir($dir) && $handle = opendir($dir))  {
            while ($file = readdir($handle))  {
                if ($file == '.' || $file == '..')  {
                    continue;
                } else if (is_dir($dir.$ds.$file))  {
                    $this->destroyDir($dir.$ds.$file);
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

    function recurse_copy($src,$dst) {
        $dir = opendir($src);
        @mkdir($dst);
        while(false !== ( $file = readdir($dir)) ) {
            if (( $file != '.' ) && ( $file != '..' )) {
                if ( is_dir($src . '/' . $file) ) {
                    $this->recurse_copy($src . '/' . $file,$dst . '/' . $file);
                }
                else {
                    copy($src . '/' . $file,$dst . '/' . $file);
                }
            }
        }
        closedir($dir);
    }

    function showInfoMessage() {
        /*
         * ToDo
         */
    }

    function showDebugMessage() {
        /*
         * ToDo
         */
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

    function backupInstallation() {
        try {
            $this->config['dbBackup'] = '/var/backups/phpietadminBackup' . time() . '.tar';
            $phar = new PharData($this->config['dbBackup']);
            $phar->buildFromDirectory($this->baseDir);
            $this->config['phpietadminDbBackup'] = '/var/backups/phpietadminDatabaseBackup' . time();
            if(!rename($this->config['databaseFile'], $this->config['phpietadminDbBackup'])) {
                throw new exception("Can't move the database to backup location!\n");
            }
        } catch (Exception $e) {
            $this->showErrorMessage("Can't create backup.\n" . $e->getMessage());
        }
    }

    function copyInstallation($destination) {
        $this->recurse_copy($this->getDir(), $destination);
    }

    function generateAuthCode() {
        global $config;
        if (file_exists($config['authFile'])) {
            echo "Code was already generated!\n";
        } else {
            $password = $this->random_password(8);
            if (file_put_contents($config['authFile'], $password) !== false) {
                echo "Success\n";
                echo "Code is " . $password . "\n";
                chown($config['authFile'], 'www-data');
            } else {
                echo "Failure\n";
            }
        }
    }

    function getDir() {
        // returns the phpietadmin-gui-$version base directory
        return __DIR__ . '/../../../';
    }
}