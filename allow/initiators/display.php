<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/initiators/display/header.html';
    require '../../views/overview/menu.html';
    try {
        $a_data2 = get_allow($ietd_init_allow);

        if ($a_data2 == "error") {
            throw new exception ("Error - Could not list initiators");
        } else {
            require '../../views/allow/initiators/display/output.html';
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }
    require '../../views/div.html';
    require '../../views/footer.html';
?>
?>