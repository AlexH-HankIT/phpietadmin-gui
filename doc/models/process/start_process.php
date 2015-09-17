<?php
$outputfile = "output.file";
$pidfile = "pid.file";
$exitcode = 'exit.file';

if (file_exists($outputfile)) {
    unlink($outputfile);
}

if (file_exists($pidfile)) {
    unlink($pidfile);
}

if (file_exists($exitcode)) {
    unlink($exitcode);
}

$cmd = "dd if=/dev/zero of=/root/image.img";
exec(sprintf("(%s; echo $? > %s) > %s 2>&1 & echo $! >> %s", $cmd, $exitcode, $outputfile, $pidfile));
