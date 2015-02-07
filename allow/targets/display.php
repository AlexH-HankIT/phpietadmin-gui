<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/targets/display/header.html';
    require '../../views/overview/menu.html';
    try {
        $a_data2 = get_allow('/etc/iet/targets.allow');

        if ($a_data2 == "error") {
            throw new exception ("Error - Could not list targets");
        } else {
            require '../../views/allow/targets/display/output.html';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }
    require '../../views/div.html';
    require '../../views/footer.html';
?>