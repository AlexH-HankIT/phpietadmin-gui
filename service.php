<?php
    require 'views/header.html';
    require 'views/nav.html';


    if (isset($_POST['start'])) {
        $output = shell_exec("{$a_config['misc']['sudo']} {$a_config['misc']['service']} {$a_config['iet']['servicename']} start");
    } else if (isset($_POST['stop'])) {
        $output = shell_exec("{$a_config['misc']['sudo']} {$a_config['misc']['service']} {$a_config['iet']['servicename']} stop");
    } else if (isset($_POST['restart'])) {
        $output = shell_exec("{$a_config['misc']['sudo']} {$a_config['misc']['service']} {$a_config['iet']['servicename']} restart");
    }

    require 'views/service/input.html';

    $return = get_service_status();
    if ($return[1]!=0) {
        require 'views/service/error.html';
    } else {
        require 'views/service/good.html';
    }
    require 'views/footer.html';
?>