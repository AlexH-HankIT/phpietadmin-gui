<?php
/**
 * Auto load classes
 *
 * @param $class
 */
function loader($class) {
    require_once BASE_DIR . '/' . str_replace('\\', '/', $class) . '.php';
}

/**
 * Version file parser
 *
 * @return mixed
 * @throws Exception
 */
function getVersionFile() {
    if (file_exists(VERSION_FILE)) {
        $versionFile = json_decode(file_get_contents(VERSION_FILE), true);
        if ($versionFile !== NULL) {
            return $versionFile;
        } else {
            throw new \Exception('Version file is invalid!');
        }
    } else {
        throw new \Exception('Version file not found!');
    }
}