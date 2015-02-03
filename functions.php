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
    $a_data= explode("\n", $data);

    // Delete all lines containing '#' and seperate remaining data by space
    for ($i=0; $i<count($a_data)-1; $i++) {
        if (strpos($a_data[$i],'#') === false) {
            # Ignore empty lines
            if (strpos($a_data[$i]," ") !== false) {
                $a_data2[$i] = explode(" ", $a_data[$i]);
            }
        }
    }

    // Rebuild array index
    $a_data2 = array_values($a_data2);

    return $a_data2;
}

function get_file_cat($file) {
    global $cat;
    $data = shell_exec("$cat $file");
    $a_data = explode("\n", $data);
    if (!empty($a_data[0])) {
        return $a_data;
    } else {
        return "error";
    }
}

function get_data_regex($array, $regex) {
    for ($i=0; $i < count($array); $i++) {
        preg_match($regex, $array[$i], $result);
        if (isset($result[1])) {
            $var[$i] = $result[1];
            $var = array_slice($var, 0);
        }
    }
    if (!empty($var)) {
        return $var;
    } else {
        return "error";
    }
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

function get_volume_groups() {
    global $sudo;
    global $vgs;
    $vg = shell_exec("$sudo $vgs --noheadings --rows");
    $vg_out = explode("\n", $vg);
    $vg_out = explode(" ", $vg_out[0]);
    $vg_out = array_filter($vg_out);
    $vg_out2 = array_values($vg_out);

    if (!empty($vg_out2)) {
        $count = count($vg_out2) ;
        $data[0] = $vg_out2;
        $data[1] = $count;
        return $data;
    } else {
        return "error";
    }
}

function get_lvm_data($bin) {
    global $sudo;
    $var = shell_exec("$sudo $bin --noheadings --units g");
    $var_out = explode("\n", $var);
    $var_out = array_filter($var_out, 'strlen');

    for ($i=0; $i < count($var_out); $i++) {
        $var_out2[$i] = explode(" ", $var_out[$i]);
        $var_out2[$i] = array_filter($var_out2[$i], 'strlen');
        $var_out2[$i] = array_values($var_out2[$i]);
    }

    return $var_out2;
}

function get_one_lvm_data($bin, $var) {
    global $sudo;
    $var = shell_exec("$sudo $bin --noheadings --units g $var");
    $var_out = explode("\n", $var);
    $var_out = array_filter($var_out, 'strlen');

    for ($i=0; $i < count($var_out); $i++) {
        $var_out2[$i] = explode(" ", $var_out[$i]);
        $var_out2[$i] = array_filter($var_out2[$i], 'strlen');
        $var_out2[$i] = array_values($var_out2[$i]);
    }

    if (!empty($var_out2)) {
        return $var_out2;
    } else {
        return "error";
    }
}
?>