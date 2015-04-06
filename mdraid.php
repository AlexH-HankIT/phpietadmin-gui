<?php
    require 'views/header.html';
    require 'views/nav.html';
    require 'views/mdraid/header.html';
    require 'views/overview/menu.html';

    $v_mdstat = file_get_contents($a_config['mdraid']['mdstat'] );
    $a_mdstat = explode ("\n", $v_mdstat);
    $a_mdstat = array_filter($a_mdstat);

    echo "<pre>";
        print_r($a_mdstat);
    echo "</pre>";

    preg_match_all("/Personalities :(.*)/", $a_mdstat[0], $pers);

    // $pers[1] Personalities



    //require 'views/mdraid/output.html';
    require 'views/footer.html';
?>