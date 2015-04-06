<?php

    require '../../views/header.html';
    require '../../views/nav.html';
    print_title("Volume Groups");
    require '../../views/overview/menu.html';

    $lvm = new Lvm;

    $data = $lvm->get_lvm_data($a_config['lvm']['vgs']);

    require '../../views/lvm/display/vg/output.html';
    require '../../views/footer.html'

?>