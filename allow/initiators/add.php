<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    print_title("Add");
    require '../../views/allow/menu.html';

    try {
        // Check if service is running and abort if not
        check_service_status();

        $volumes = file_get_contents($a_config['iet']['proc_volumes']);
        preg_match_all("/name:(.*)/", $volumes, $a_name);
        $a_name = $a_name[1];

        $a_initiators = get_allow($a_config['iet']['ietd_init_allow']);

        if ($a_initiators !== "error") {
            for ($i = 0; $i < count($a_initiators); $i++) {
                $a_initiators2[$i] = $a_initiators[$i][0];
            }
            $a_name = array_diff($a_name, $a_initiators2);
        }

        $a_name = array_values($a_name);

        if (empty($_POST['IQNs'])) {
            if (empty($a_name)) {
                throw new Exception("Error - No targets found or allow rules already set");
            } else {
                require '../../views/allow/initiators/add/input.html';
            }
        } else {
            $d = $_POST['IQNs'] - 1;
            $NAME = $a_name[$d];
            $current = "\n$NAME $_POST[ip]\n";
            file_put_contents($a_config['iet']['ietd_init_allow'], $current, FILE_APPEND | LOCK_EX);
            require '../../views/allow/initiators/add/success.html';
        }

    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }
    require '../../views/div.html';
    require '../../views/footer.html';
?>