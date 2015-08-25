<?php
// todo
// build auth code menu in frontend
// add backup function
// add restore function
// add remove feature (for testing purpose)

class Logic {
    protected $phpietadmin_base_dir;
    protected $backup_dir = '/var/backup';
    protected $sudoer_file = '/etc/sudoers.d/phpietadmin';
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
    protected $phpietadmin_version_file = 'https://raw.githubusercontent.com/MrCrankHank/phpietadmin/master/version.json';
    protected $debug = false;

    protected function __construct($phpietadmin_base_dir) {
        $this->phpietadmin_base_dir = $phpietadmin_base_dir;
        $this->database_install_file = $this->phpietadmin_base_dir . '/install/database.new.sql';
        $this->database_update_file = $this->phpietadmin_base_dir . '/install/database.update.sql';
        $this->iet_apache_config = $this->phpietadmin_base_dir . '/install/phpietadmin';
        $this->auth_code_file = $this->phpietadmin_base_dir . '/install/auth.xml';
        $this->database = $this->phpietadmin_base_dir . '/app/config.db';
    }

    protected function recurse_copy($src,$dst) {
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

    protected function check_sapi() {
        $this->log_debug('Checking if the script is called via cli...');
        if (PHP_SAPI !== 'cli') {
            throw new exception('This script is only intended for use via the php cli!');
        }
    }

    protected function check_language() {
        $this->log_debug('Checking if the language is supported...');
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
        echo "--debug/-d output debug information\n";
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

    protected function log_debug($message) {
        if ($this->debug === true) {
            echo "Debug: " . $message . "\n";
        }
    }

    protected function check_root() {
        $this->log_debug('Checking if the script was exectued as root...');
        if (posix_getuid() != 0){
            throw new exception('This script requires root permissions!');
        }
    }

    protected function update_package_database() {
        $this->log_debug('Updating the apt database...');
        exec('apt-get update', $output, $code);

        if ($code != 0) {
            throw new exception('Could not update the package database!');
        }
    }

    protected function install_packages() {
        $this->log_debug('Installing the packages...');
        exec('apt-get install -y build-essential wget iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-$(uname -r) sqlite3 php5-sqlite lsb-release lvm2', $output, $code);

        if ($code != 0) {
            throw new exception('Could not install the packages!');
        }
    }

    /**
     * sudoer file to give the webserver permission to execute the needed binaries
     */
    protected function symlink_sudoer_file() {
        $this->log_debug('Deleting old sudoer symlink...');
        if (file_exists($this->sudoer_file)) {
            unlink($this->sudoer_file);
        }

        $this->log_debug('Create new sudoer link...');
        if (symlink($this->phpietadmin_base_dir . '/install/sudoer', $this->sudoer_file) === false) {
            throw new exception('Could not create a symlink for the sudoer file!');
        }

        // Be sure to secure the file...
        chgrp($this->sudoer_file, 'root');
        chown($this->sudoer_file, 'root');
        chmod($this->sudoer_file, 0600);
    }

    /**
     * change permissions of iet folder to give the webserver access
     */
    protected function change_ietd_permission() {
        $this->log_debug('Change the permission of the ietd folder...');

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->iet_dir));
        foreach($iterator as $item) {
            chmod($item, 0770);
            chown($item, 'www-data');
        }
    }

    /**
     * Sanitize config files
     */
    protected function sanitize_config_files() {
        $this->log_debug('Sanitize the default config files...');
        file_put_contents($this->iet_default_file, str_replace('false', 'true', file_get_contents($this->iet_default_file)));
        file_put_contents($this->iet_initiators_allow_file, str_replace('ALL ALL', '', file_get_contents($this->iet_initiators_allow_file)));
    }

    /**
     * detect default apache configuration and delete it
     */
    protected function delete_default_apache_config() {
        $this->log_debug('Delete default apache config...');
        if (file_exists($this->apache_dir_sites_enabled . '/000-default')) {
            unlink($this->apache_dir_sites_enabled . '/000-default');
        } else if (file_exists($this->apache_dir_sites_enabled . '/000-default.conf')) {
            unlink($this->apache_dir_sites_enabled . '/000-default.conf');
        }
    }

    protected function symlink_phpietadmin_apache_config() {
        $this->log_debug('Create symlink for apache config...');
        $version = shell_exec('lsb_release --release --short');
        if ($version[0] == 7) {
            // wheezy
            if (file_exists($this->apache_dir_sites_enabled . '/phpietadmin')) {
                unlink($this->apache_dir_sites_enabled . '/phpietadmin');
            }

            symlink($this->iet_apache_config, $this->apache_dir_sites_enabled . '/phpietadmin');
        } else if ($version[0] == 8) {
            // jessie
            if (file_exists($this->apache_dir_sites_enabled . '/phpietadmin.conf')) {
                unlink($this->apache_dir_sites_enabled . '/phpietadmin.conf');
            }

            symlink($this->iet_apache_config, $this->apache_dir_sites_enabled . '/phpietadmin.conf');
        } else {
            throw new exception('This operating system is not supported by phpietadmin!');
        }
    }

    protected function restart_apache() {
        $this->log_debug('Restarting apache...');
        shell_exec('service apache2 restart');
    }

    protected function restart_ietd() {
        $this->log_debug('Restarting iscsitarget...');
        shell_exec('service iscsitarget restart');
    }

    protected function edit_database($action) {
        if ($action == 'install') {
            $this->log_debug('Create the database...');
            shell_exec('sqlite3 ' . $this->database . ' < ' . $this->database_install_file);
        } else if ($action == 'update') {
            $this->log_debug('Update the database...');
            shell_exec('sqlite3 ' . $this->database . ' < ' . $this->database_update_file);
        } else {
            throw new exception('Invalid edit_database action!');
        }

        $this->log_debug('Change database permission...');

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->iet_dir));
        foreach($iterator as $item) {
            chmod($item, 0660);
            chgrp($item, 'www-data');
            chown($item, 'www-data');
        }
    }

    protected function enable_apache_mods() {
        $this->log_debug('Enable apache mods...');
        shell_exec('a2enmod rewrite');
    }

    /**
     * generate authorization code file
     * since this is only a one time password
     * it doesn't have to be that strong
     * the auth code is needed to set a password the
     * login user in phpietadmin
     */
    protected function generate_auth_code() {
        $this->log_debug('Generating auth code for first login...');
        $auth_code = hash('crc32', time());

        // generate xml
        $xml = new SimpleXMLElement('<authentication/>');
        $authcodes = $xml->addChild('authcodes');
        $authcode = $authcodes->addChild('authcode');
        $authcode->addChild('code', $auth_code);

        file_put_contents($this->auth_code_file, $xml->asXML());
        echo 'Auth code for first login is: ' . $auth_code . "\n";
    }

    protected function extract_phpietadmin_files() {
        $this->log_debug('Get up2date phpietadmin version...');

        $version = json_decode(file_get_contents($this->phpietadmin_version_file), true);

        $this->log_debug('Download the files from ' . $version[2]['downloadurl']);

        set_time_limit(0);
        $fp = fopen('/tmp/' . $version[1]['version_nr'] . '.zip', 'w+');//This is the file where we save the    information
        $ch = curl_init($url = $version[2]['downloadurl']);
        curl_setopt($ch, CURLOPT_TIMEOUT, 50);
        curl_setopt($ch, CURLOPT_FILE, $fp); // write curl response to file
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_exec($ch); // get curl response
        $this->log_debug(curl_error($ch));
        curl_close($ch);
        fclose($fp);

        $this->log_debug('Extracting the files...');
        $zip = new ZipArchive;
        if ($zip->open('/tmp/' . $version[1]['version_nr'] . '.zip') === true) {
            $zip->extractTo('/usr/share');
            $zip->close();
        } else {
            throw new exception('Cannot open the phpietadmin zip file!');
        }

        if (file_exists('/tmp/' . $version[1]['version_nr'] . '.zip')) {
            unlink('/tmp/' . $version[1]['version_nr'] . '.zip');
        }

        chdir('/usr/share');
        if(rename('phpietadmin-' . $version[1]['version_nr'], 'phpietadmin') === false) {
            throw new exception('Cannot rename the phpietadmin directory');
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->phpietadmin_base_dir . '/app'));
        foreach($iterator as $item) {
            chown($item, 'www-data');
        }

        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->phpietadmin_base_dir . '/public'));
        foreach($iterator as $item) {
            chown($item, 'www-data');
        }
    }

    protected function delete_phpietadmin_dir() {
        $this->log_debug('Delete the phpietadmin directory');
        $this->destroy_dir($this->phpietadmin_base_dir);
    }

    protected function delete_phpietadmin_config_files() {
        $this->log_debug('Delete the phpietadmin config files');
        if (is_link($this->apache_dir_sites_enabled . '/phpietadmin')) {
            unlink($this->apache_dir_sites_enabled . '/phpietadmin');
        }

        if (is_link($this->apache_dir_sites_enabled . '/phpietadmin.conf')) {
            unlink($this->apache_dir_sites_enabled . '/phpietadmin.conf');
        }

        if (is_link($this->sudoer_file)) {
            unlink($this->sudoer_file);
        }
    }

    protected function display_packages() {
        echo 'This is a list of packages i used: ' . "\n";
        echo 'Since i don\'t know which one you actually use, you must delete them manually!' . "\n";
        echo 'build-essential wget iscsitarget iscsitarget-dkms apache2 sudo libapache2-mod-php5 linux-headers-$(uname -r) sqlite3 php5-sqlite lsb-release lvm2' . "\n";
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
            $options = getopt('a:hvd', array('action:', 'help', 'version', 'debug'));

            // enable debug mode
            if (isset($options['d']) || isset($options['debug'])) {
                $this->debug = true;
            }

            if (empty($options)) {
                $this->display_help();
            } else if (isset($options['h']) || isset($options['help'])) {
                $this->display_help();
            } else if (isset($options['v'])|| isset($options['version'])) {
                echo "0.1\n";
            } else if ((isset($options['action']) || isset($options['a'])) && (!empty($options['a']) || !empty($options['action']))) {
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
                } else if ($options['a'] == 'remove') {
                    $this->remove();
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