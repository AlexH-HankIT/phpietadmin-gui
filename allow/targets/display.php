<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/targets/display/header.html';
    require '../../views/overview/menu.html';

    $a_data2 = get_allow('/etc/iet/targets.allow');

    require '../../views/allow/targets/display/output.html';
    require '../../views/div.html';
    require '../../views/footer.html';
?>