<?php
    require 'Target.php';
    require '../../Database.php';

    $database = new Database();

    $models['database'] = $database;

    $target = new Target($models, 'iqn.2014-12.com.example.iscsi:aergabdfgbydgbxcvbbvxfbg');

//	$target = new Target($models);

// print_r($target->get_acls());

// $target->add_acl(1);

    // $target->add_lun('/dev/VG_data02/tesrat', 'blockio', 'wt');

// $target->target_status;

  // print_r($target->get_action_result());

    //print_r($target->return_target_data());

    // print_r($target->get_action_result());
    //$target->delete_lun_from_iqn();
        //$target->delete_lun('/dev/VG_data01/test7', true);

    //$target->delete_target(false, true, false);
//$target->get_settings();

//$target->add_setting('HeaderDigest', 'CRC32C');
//$target->add_user(1, true, 'OutgoingUser');
// $target->delete_acl('127.0.0.1');

$target->delete_user(1, true, 'OutgoingUser');

$target->get_all_settings();



       print_r($target->g