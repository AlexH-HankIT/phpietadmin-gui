<?php

    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/lvm/display/vg/header.html';
    require '../../views/overview/menu.html';

    $data = get_lvm_data($vgs);

    require '../../views/lvm/display/vg/output.html';
    require '../../views/div.html';
require '../../views/footer.html'

?>