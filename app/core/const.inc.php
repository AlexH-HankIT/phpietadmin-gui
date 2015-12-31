<?php
/**
 * Define paths to directories and files
 */
define('BASE_DIR', __DIR__ . '/../..');
define('INSTALL_DIR', BASE_DIR . '/install');
define('APP_DIR', BASE_DIR . '/app');
define('PUBLIC_DIR', BASE_DIR . '/public');
define('CORE_DIR', APP_DIR . '/core');
define('CONTROLLER_DIR', APP_DIR . '/controllers');
define('MODEL_DIR', APP_DIR . '/models');
define('VIEW_DIR', APP_DIR . '/views');
define('EXCEPTION_DIR', APP_DIR . '/exceptions');

/**
 * Misc
 */
// If you change this, you have to adjust your htaccess/apache2 config file
define('PROJECT_NAME', 'phpietadmin');
define('WEB_PATH', '/phpietadmin');
define('DB_FILE', APP_DIR . '/config.db');
define('AUTH_FILE', APP_DIR . '/auth');
define('VERSION_FILE', BASE_DIR . '/version.json');

/*
 * Indicates the application mode, values are 'dev' or 'pro'
 * If status equals 'dev', the application will always "think" it is correctly installed
 * even if the version file says otherwise or the database is missing
 * In productive use this *must* be set to pro
 */
if (file_exists(BASE_DIR . '/dev')) {
    define('MODE', 'dev');
} else {
    define('MODE', 'pro');
}