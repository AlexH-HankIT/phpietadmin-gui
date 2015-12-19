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
define('WEB_PATH', '/phpietadmin');
define('DB_FILE', APP_DIR . '/config.db');
define('AUTH_FILE', APP_DIR . '/auth');
define('VERSION_FILE', BASE_DIR . '/version.json');

// Indicates the application mode, values are dev and pro
// If set to dev the status value from the version file is ignored
define('MODE', 'dev');