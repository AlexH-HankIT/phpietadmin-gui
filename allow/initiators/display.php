<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    print_title("Targets");
    require '../../views/overview/menu.html';
    try {
        $a_data2 = get_allow($a_config['iet']['ietd_init_allow']);

        if ($a_data2 == "error") {
            throw new exception ("Error - Could not list initiators");
        } else {
            require '../../views/allow/initiators/display/output.html';
        }
    } catch (Exception $e) {
        print_error($e);
    }
    require '../../views/footer.html';
?>
?>