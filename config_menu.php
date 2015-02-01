<?php
    $options = array(
        1 => "iqn",
        2 => "service",
        3 => "proc_volumes",
        4 => "proc_sessions",
        5 => "ietd_config_file",
        6 => "ietd_allow",
        7 => "ietadm",
        8 => "sudo",
        9 => "cat",
        10 => "vgs",
        11 => "lvs",
        12 => "pvs",
        13 => "lsblk",
    );

    require 'views/header.html';
    require 'views/nav.html';
    require 'views/config_menu/header.html';

    foreach ($options as $a) {
        set_config_options($a);
    }
echo '</form>';

    require 'views/div.html';
    require 'views/footer.html';


?>