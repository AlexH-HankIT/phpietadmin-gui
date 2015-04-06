<?php
    require "$_SERVER[DOCUMENT_ROOT]/phpietadmin/functions.php";
    require "$_SERVER[DOCUMENT_ROOT]/phpietadmin/classes/Lvm.php";
    require "$_SERVER[DOCUMENT_ROOT]/phpietadmin/classes/Layout.php";
    $a_config = parse_ini_file("$_SERVER[DOCUMENT_ROOT]/phpietadmin/config.ini.php", true);

    $layout = new Layout;
?>