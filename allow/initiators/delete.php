<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/initiators/delete/header.html';
    require '../../views/allow/menu.html';

    try {
        // Check if service is running and abort if not
        check_service_status();

        $a_initiators = get_allow($ietd_init_allow);

        if ($a_initiators == "error") {
            throw new Exception("Error - No allow rules found");
        } else {
            for ($i=0; $i < count($a_initiators); $i++) {
                $a_initiators2[$i] = $a_initiators[$i][0];
            }

            if (!empty($_POST['IQNs2'])) {
                $d = $_POST['IQNs2'] - 1;
                $NAME = $a_initiators2[$d];
                deleteLineInFile($ietd_init_allow, "$NAME");
                require '../../views/allow/initiators/delete/success.html';

            } else {
                require '../../views/allow/initiators/delete/input.html';
            }

        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }

    require '../../views/div.html';
    require '../../views/footer.html';
?>