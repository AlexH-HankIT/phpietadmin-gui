<?php
namespace app\models;

class Misc {
    /**
     * Auto load classes
     *
     * @param $class
     */
    public static function loader($class) {
        // Set class name to upper case
        $class = explode('\\', $class);
        $className = end($class);
        $key = key($class);
        $class[$key] = ucfirst($className);
        $class = implode($class, '\\');
        require_once BASE_DIR . '/' . str_replace('\\', '/', $class) . '.php';
    }

    /**
     * Version file parser
     *
     * @return mixed
     * @throws \Exception
     */
    public static function getVersionFile() {
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

    /**
     * Checks if an incoming request is an ajax
     *
     * @return bool
     */
    public static function isXHttpRequest() {
        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Collects data for the phpietadmin dashboard
     *
     * @return array
     */
    public static function get_dashboard_data() {
        $data['hostname'] = file_get_contents('/etc/hostname');

        // get version and release
        $versionFile = self::getVersionFile();
        $data['phpietadminversion'] = $versionFile['version'];
        $data['release'] = $versionFile['release'];

        $data['distribution'] = shell_exec('lsb_release -sd');

        $hwdata = file('/proc/cpuinfo');
        $hwdata[4] = str_replace("model", '', $hwdata[4]);
        $hwdata[4] = str_replace("name", '', $hwdata[4]);
        $data['cpu'] = str_replace(":", '', $hwdata[4]);

        $data['uptime'] = shell_exec('uptime -p');
        $data['systemstart'] = shell_exec('uptime -s');

        preg_match('/load average: (.*)/', shell_exec('uptime'), $matches);
        $data['currentload'] = $matches[1];

        $mem = file('/proc/meminfo');
        preg_match('/[0-9]+/', $mem[0], $matches);
        $data['memtotal'] = intval($matches[0] / 1024);

        preg_match('/[0-9]+/', $mem[1], $matches);
        $data['memused'] = intval($matches[0] / 1024);

        $data['systemtime'] = shell_exec('date');
        $data['kernel'] = shell_exec('uname -r');

        return $data;
    }
}
