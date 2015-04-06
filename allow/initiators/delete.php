<?php
    require '../../includes.php';
    $layout->print_nav();
    $layout->print_title("Delete");
    require '../../views/allow/menu.html';

    try {
        // Check if service is running and abort if not
        check_service_status();

        $a_initiators = get_allow($a_config['iet']['ietd_init_allow']);

        if ($a_initiators == "error") {
            throw new Exception("Error - No allow rules found");
        } else {
            for ($i=0; $i < count($a_initiators); $i++) {
                $a_initiators2[$i] = $a_initiators[$i][0];
            }

            if (!empty($_POST['IQNs2'])) {
                $d = $_POST['IQNs2'] - 1;
                $NAME = $a_initiators2[$d];
                deleteLineInFile($a_config['iet']['ietd_init_allow'], "$NAME");
                require '../../views/allow/initiators/delete/success.html';

            } else {
                require '../../views/allow/initiators/delete/input.html';
            }

        }
    } catch (Exception $e) {
        $layout->print_error($e);
    }

    $layout->print_footer();
?>