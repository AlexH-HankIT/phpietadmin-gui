<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/initiators/add/header.html';
    require '../../views/allow/menu.html';

    try {
        // Check if service is running and abort if not
        check_service_status();

        $volumes = file_get_contents($proc_volumes);
        preg_match_all("/name:(.*)/", $volumes, $a_name);

        if (empty($_POST['IQNs'])) {
            if (empty($volumes)) {
                throw new Exception("Error - No targets found");
            } else {
                require '../../views/allow/initiators/add/input.html';
            }
        } else {
            if (empty($_POST['ip'])) {
                throw new Exception("Error - Please enter a IP address");
            } elseif (empty($_POST['IQNs'])) {
                throw new Exception("Error - Please choose a target");
            } else {
                $d = $_POST['IQNs'] - 1;
                $NAME = $a_name[1][$d];
                $current = "\n$NAME $_POST[ip]\n";
                file_put_contents($ietd_init_allow, $current, FILE_APPEND | LOCK_EX);
            }
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }

    require '../../views/div.html';
    require '../../views/footer.html';
?>