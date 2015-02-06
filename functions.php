<?php
require 'config.php';

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
    for ($i=0; $i<count($a_data); $i++) {
        if (strpos($a_data[$i],'#') === false) {
            # Ignore empty lines
            if (strpos($a_data[$i]," ") !== false) {
                $a_data2[$i] = explode(" ", $a_data[$i]);
            }
        }
    }

    // Rebuild array index and return
    return array_values($a_data2);
}

function get_service_status() {
    global $sudo;
    global $service;
    global $service_name;
    exec("$sudo $service $service_name status", $status, $result );
    $return[0] = $status;
    $return[1] = $result;
    return $return;
}

function check_service_status() {
    global $service_name;
    $result = get_service_status();
    if($result[1] !== 0) {
        throw new Exception("Error - Service $service_name is not running.");
    }
}

// LVM
function get_volume_groups() {
    global $sudo;
    global $vgs;

    // Read output from shell in var
    $vg = shell_exec("$sudo $vgs --rows --noheadings");

    // Take only first line, since it contains the names of all groups
    $vg = strtok($vg, "\n");

    // Create array from string
    $a_vg = explode(" ", $vg);

    // Filter empty array elements and recreate index
    $a_vg = array_values(array_filter($a_vg));

    // Return if not empty
    if (!empty($a_vg)) {
        return $a_vg;
    } else {
        return "error";
    }
}

function get_lvm_data($bin, $name = 0) {
    global $sudo;

    // Read output from shell in var
    // Use specific name if supplied
    if ($name === 0) {
        $var = shell_exec("$sudo $bin --noheadings --units g");
    } else {
        $var = shell_exec("$sudo $bin --noheadings --units g $name");
    }

    // Explode string by line
    $a_var = array_filter(explode("\n", $var));

    for ($i=0; $i < count($a_var); $i++) {
        // Create array for every line
        $data[$i] = explode(" ", $a_var[$i]);

        // Filter empty lines
        $data[$i] = array_filter($data[$i], 'strlen');

        // Recreate array index
        $data[$i] = array_values($data[$i]);
    }

    // Return if not empty
    if (!empty($data)) {
        return $data;
    } else {
        return "error";
    }
}

function get_logical_volumes($vgroup) {
    global $sudo;
    global $lvs;
    $lv = shell_exec("$sudo $lvs --noheadings --units g $vgroup");
    $lv_out = explode("\n", $lv);
    $count = count($lv_out)-1;

    for ($i=0; $i < $count; $i++) {
        $lv = shell_exec("$sudo $lvs --noheadings --units g $vgroup");
        $lv_out = explode("\n", $lv);
        $lv_out = explode(" ", $lv_out[$i]);
        $lv_out = array_filter($lv_out, 'strlen');
        $lvs2[$i] = array_slice($lv_out, 0);
    }

    if (!empty($lvs2)) {
        return $lvs2;
    } else {
        return "error";
    }
}
?>