<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/initiators/display/header.html';
    require '../../views/overview/menu.html';

    $a_data2 = get_allow('/etc/iet/initiators.allow');

    require '../../views/allow/initiators/display/output.html';
    require '../../views/div.html';
    require '../../views/footer.html';
?>