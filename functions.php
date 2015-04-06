<?php
    $a_config = parse_ini_file("config.ini.php", true);

    function deleteLineInFile($file, $string) {
        $i=0;$array=array();
        $read = fopen($file, "r") or die("can't open the file $file");
        while(!feof($read)) {
            $array[$i] = fgets($read);
            ++$i;
        }
        fclose($read);
        $write = fopen($file, "w") or die("can't open the file $file");
        foreach($array as $a) {
            if(!strstr($a,$string)) fwrite($write,$a);
        }
        fclose($write);
    }

    function add_LineToFile($file, $line) {
        $current = "$line\n";
        file_put_contents($file, $current, FILE_APPEND | LOCK_EX);
    }

    function get_allow($file) {
        // Read data in var
        $data = file_get_contents("$file");

        // Create array
        $a_data = explode("\n", $data);

        // Filter empty elements
        $a_data = array_values(array_filter($a_data));

        // Delete all lines containing '#' and seperate remaining data by space
        for ($i = 0; $i < count($a_data); $i++) {
            if (strpos($a_data[$i], '#') === false) {
                # Ignore empty lines
                if (strpos($a_data[$i], " ") !== false) {
                    $a_data2[$i] = explode(" ", $a_data[$i]);
                }
            }
        }

        // Rebuild array index and return
        if (!empty($a_data2)) {
            return array_values($a_data2);
        } else {
            return "error";
        }
    }

    function get_service_status() {
        global $a_config;
        exec("{$a_config['misc']['sudo']} {$a_config['misc']['service']} {$a_config['iet']['servicename']} status", $status, $result );
        $return[0] = $status;
        $return[1] = $result;
        return $return;
    }

    function check_service_status() {
        global $a_config;
        $result = get_service_status();
        if($result[1] !== 0) {
            throw new Exception("Error - Service {$a_config['iet']['servicename']} is not running.");
        }
    }

    function print_title($var) { ?>
            <div id="main">
            <h1><?php echo $var ?></h1>
    <?php }

    function print_error($e) {
        $error = $e->getMessage(); ?>
        <div id="leftDiv">
            <h4><?php echo $error ?></h4>
        </div>
    <?php }
?>