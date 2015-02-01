<?php
    require 'views/header.html';
    require 'views/nav.html';


    if (isset($_POST['start'])) {
        $output = shell_exec("$sudo $service iscsitarget start");
    } else if (isset($_POST['stop'])) {
        $output = shell_exec("$sudo $service iscsitarget stop");
    } else if (isset($_POST['restart'])) {
        $output = shell_exec("$sudo $service iscsitarget restart");
    }

    require 'views/service/input.html';

    $return = get_service_status($service);
    if ($return[1]!=0) {
        require 'views/service/error.html';
    } else {
        require 'views/service/good.html';
    }
    require 'views/footer.html';
?>