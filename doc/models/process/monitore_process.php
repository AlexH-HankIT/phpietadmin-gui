<?php
$outputfile = "output.file";
$pidfile = "pid.file";
$exitcode = 'exit.file';

function isRunning($pid){
    try{
        $result = shell_exec(sprintf("ps %d", $pid));
        if( count(preg_split("/\n/", $result)) > 2){
            return true;
        }
    }catch(Exception $e){}

    return false;
}

if (file_exists($outputfile) && filesize($outputfile) !== 0) {
    $data['output'] = trim(rtrim(file_get_contents($outputfile)));
}

if (file_exists($exitcode)) {
    $data['return_var'] = rtrim(file_get_contents($exitcode));
}

if (file_exists($pidfile)) {
    $data['status'] = isRunning(file_get_contents($pidfile));
} //else {
//              $data['status'] = true;
//      }

var_dump($data);