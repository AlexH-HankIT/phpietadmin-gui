<?php
// todo2
// testing
// chmod & chown recursive
    // $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($pathname));
    //foreach($iterator as $item) {
    //    chmod($item, $filemode);
    //}
// support long parameters
// logging and error handling
// exception handling
// build auth code menu in frontend
// add backup function
// add restore function
// add remove feature (for testing purpose)

class Logic {
    protected $phpietadmin_base_dir;
    protected $backup_dir = '/var/backup';
    protected $time;
    protected $sudoer_file = '/etc/sudoers.d/phpietadmin';
    protected $data_dir;
    protected $iet_dir = '/etc/iet';
    protected $iet_default_file = '/etc/default/iscsitarget';
    protected $iet_initiators_allow_file = '/etc/iet/initiators.allow';
    protected $iet_apache_config;
    protected $apache_dir = '/etc/apache2';
    protected $apache_dir_sites_enabled = '/etc/apache2/sites-enabled';
    protected $database_install_file;
    protected $database_update_file;
    protected $database;
    protected $auth_code_file;
    protected $phpietadmin_version_file = 'https://raw.githubusercontent.com/MrCrankHank/phpietadmin/master/version';

    protected function __construct($phpietadmin_base_dir) {
        $this->phpietadmin_base_dir = $phpietadmin_base_dir;
        $this->time = time();
        $this->data_dir = '/tmp/phpietadmin_' . $this->time;
        $this->database_install_file = $this->phpietadmin_base_dir . '/install/database.new.sql';
        $this->database_update_file = $this->phpietadmin_base_dir . '/install/database.update.sql';
        $this->iet_apache_config = $this->phpietadmin_base_dir . '/install/phpietadmin';
        $this->auth_code_file = $this->phpietadmin_base_dir . '/install/auth_code';
        $this->database = $this->phpietadmin_base_dir . '/app/config.db';
    }

    protected function check_sapi() {
        if (PHP_SAPI !== 'cli') {
            throw new exception('This script is only intended for use via the php cli!');
        }
    }

    protected function check_language() {
        $lang = shell_exec('echo $LANG');
        if (strpos($lang,'de_DE') === true || strpos($lang,'en_EN') === true) {
            throw new exception('Error - this software only runs on systems with de_DE or en_EN localization!');
        }
    }

    protected function display_help() {
        echo "This is the phpietadmin-cli tool\n\n";
        echo "Options: \n";
        echo "--help/-h display this help \n";
        echo "--action/-a install/update/remove/backup/restore phpietadmin\n";
        echo "--version/-v display version information\n";
    }

    /*****
     *@dir - Directory to destroy
     *@virtual[optional]- whether a virtual directory
     *@link http://webdeveloperplus.com/php/21-really-useful-handy-php-code-snippets/
     */
    protected function destroy_dir($dir, $virtual = false) {
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

    protected function check_root() {
        if (posix_getuid() != 0){
            throw new exception('This script requires root permissions!');
        }
    }

    protected function update_package_database() {
        exec('apt-get update', $output, $code);

        if ($code != 0) {
            throw new exception('Could not update the package database!');
        }
    }

    protected function install_packages() {
        exec('apt-get install -y build-essential wget iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-$(uname -r) sqlite3 php5-sqlite lsb-release lvm2', $output, $code);

        if ($code != 0) {
            throw new exception('Could not install the packages!');
        }
    }

    protected function copy_phpietadmin_installation() {
        if (copy($this->data_dir, $this->phpietadmin_base_dir) === false) {
            throw new exception('Could not copy the phpietadmin files!');
        }
    }

    /**
     * sudoer file to give the webserver permission to execute the needed binaries
     */
    protected function symlink_sudoer_file() {
        if (file_exists($this->sudoer_file)) {
            unlink($this->sudoer_file);
        }

        if (symlink($this->sudoer_file, $this->data_dir . '/install/sudoer') === false) {
            throw new exception('Could not create a symlink for the sudoer file!');
        }
    }

    /**
     * change permissions of iet folder to give the webserver access
     */
    protected function change_ietd_permission() {
        if (chgrp($this->iet_dir, 'www-data') === false || chmod($this->iet_dir, 0770) === false) {
            throw new exception('Could not change the permissions for the ietd dir!');
        }
    }

    /**
     * Sanitize config files
     */
    protected function sanitize_config_files() {
        file_put_contents($this->iet_default_file, str_replace('false', 'true', file_get_contents($this->iet_default_file)));
        file_put_contents($this->iet_initiators_allow_file, str_replace('ALL ALL', '', file_get_contents($this->iet_initiators_allow_file)));
    }

    /**
     * detect default apache configuration and delete it
     */
    protected function delete_default_apache_config() {
        if (file_exists($this->apache_dir_sites_enabled . '/000-default')) {
            unlink($this->apache_dir_sites_enabled . '/000-default');
        } else if (file_exists($this->apache_dir_sites_enabled . '/000-default.conf')) {
            unlink($this->apache_dir_sites_enabled . '/000-default.conf');
        }
    }

    protected function symlink_phpietadmin_apache_config() {
        $version = shell_exec('lsb_release --release --short');
        if ($version[0] == 7) {
            // wheezy
            unlink($this->apache_dir_sites_enabled . '/phpietadmin');
            symlink($this->apache_dir_sites_enabled . '/phpietadmin', $this->iet_apache_config);
        } else if ($version[0] == 8) {
            // jessie
            unlink($this->apache_dir_sites_enabled . '/phpietadmin.conf');
            symlink($this->apache_dir_sites_enabled . '/phpietadmin.conf', $this->iet_apache_config);
        } else {
            throw new exception('This operating system is not supported by phpietadmin!');
        }
    }

    protected function restart_apache() {
        shell_exec('service apache2 restart');
    }

    protected function restart_ietd() {
        shell_exec('service iscsitarget restart');
    }

    protected function edit_database($action) {
        if ($action == 'install') {
            shell_exec('sqlite3 ' . $this->database . ' < ' . $this->database_install_file);
        } else if ($action == 'update') {
            shell_exec('sqlite3 ' . $this->database . ' < ' . $this->database_update_file);
        } else {
            throw new exception('Invalid edit_database action!');
        }

        chown($this->iet_dir, 'www-data');
        chgrp($this->iet_dir, 'www-data');
        chmod($this->iet_dir, 0660);
    }

    protected function enable_apache_mods() {
        shell_exec('a2endmod rewrite');
    }


    /**
     * generate authorization code file
     * since this is only a one time password
     * it doesn't have to be that strong
     * the auth code is needed to set a password the
     * login user in phpietadmin
     */
    protected function generate_auth_code() {
        $auth_code = hash('crc32', time());
        file_put_contents($this->auth_code_file, $auth_code);
        chgrp($this->iet_dir, 'www-data');
        chmod($this->iet_dir, 0640);
    }

    protected function extract_phpietadmin_files() {
        $version = file_get_contents($this->phpietadmin_version_file);

        shell_exec('wget https://github.com/MrCrankHank/phpietadmin/archive/' .  $version . '.zip --output-document=' . '/tmp');

        $zip = new ZipArchive;

        if ($zip->open('/tmp/' . $version . '.zip') === true) {
            mkdir($this->data_dir);
            $zip->extractTo($this->data_dir);
            $zip->close();
        } else {
            throw new exception('Cannot open the phpietadmin zip file!');
        }
    }

    protected function delete_phpietadmin_dir() {
        return $this->destroy_dir($this->phpietadmin_base_dir);
    }

    protected function delete_temp_phpietadmin_dir() {
        return $this->destroy_dir($this->data_dir);
    }

    protected function delete_phpietadmin_config_files() {
        if (file_exists($this->apache_dir_sites_enabled . '/phpietadmin')) {
            unlink($this->apache_dir_sites_enabled . '/phpietadmin');
        }

        if (file_exists($this->apache_dir_sites_enabled . '/phpietadmin.conf')) {
            unlink($this->apache_dir_sites_enabled . '/phpietadmin.conf');

        }

        if (file_exists($this->sudoer_file)) {
            unlink($this->sudoer_file);
        }
    }

    protected function purge_packages() {
        shell_exec('apt-get purge -y build-essential wget iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-$(uname -r) sqlite3 php5-sqlite lsb-release lvm2');
    }
}

require_once 'instructions.php';

class Cli extends Instructions {
    public function __construct() {
        try {
            parent::__construct();

            // Check environment
            $this->check_sapi();
            $this->check_language();
            $this->check_root();

            // get cli options
            $options = getopt('a:hv', array('action:', 'help', 'version'));

            if (empty($options)) {
                $this->display_help();
            } else if (isset($options['h']) || isset($options['help'])) {
                $this->display_help();
            } else if (isset($options['v'])|| isset($options['version'])) {
                echo "0.1\n";
            } else if (isset($options['action'], $options['a']) && !empty($options['a']) || !empty($options['action'])) {
                if ($options['a'] == 'update') {
                    if (is_dir($this->phpietadmin_base_dir)) {
                        $this->update();
                        echo "Don't delete the install folder, since it contains important configuration files! \n";
                    } else {
                        throw new exception('Phpietadmin is not installed on this system!');
                    }
                } else if ($options['a'] == 'install') {
                    if (is_dir($this->phpietadmin_base_dir)) {
                        throw new exception('Phpietadmin is already installed on this system!');
                    } else {
                        $this->install();
                    }
                } else {
                    $this->display_help();
                }
            } else {
                $this->display_help();
            }
        } catch (exception $e) {
            echo $e->getMessage() . "\n";
        }
    }
}

$cli = new Cli();