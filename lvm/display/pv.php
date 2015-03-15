<?php

    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/lvm/display/pv/header.html';
    require '../../views/overview/menu.html';

    $data = get_lvm_data($a_config['lvm']['pvs']);

    require '../../views/lvm/display/pv/output.html';
    require '../../views/div.html';
    require '../../views/footer.html';

?>