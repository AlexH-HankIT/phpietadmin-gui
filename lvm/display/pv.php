<?php
    require '../../includes.php';
    $layout->print_nav();
    $layout->print_title("Physical Volumes");
    require '../../views/overview/menu.html';

    // Create lvm object
    $lvm = new Lvm;

    $data = $lvm->get_lvm_data($a_config['lvm']['pvs']);

    require '../../views/lvm/display/pv/output.html';
    $layout->print_footer();
?>