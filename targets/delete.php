<?php
    require '../views/header.html';
    require '../views/nav.html';
    require '../views/targets/delete/header.html';
    require '../views/targets/menu.html';

    try {
        // Check if service is running and abort if not
        check_service_status();

        $volumes = file_get_contents($a_config['iet']['proc_volumes']);
        if (empty($volumes)) {
            throw new Exception("Error - No targets found");
        } else {
            preg_match_all("/name:(.*)/", $volumes, $a_name);
            preg_match_all("/tid:([0-9].*?) /", $volumes, $a_tid);

            if (!isset($_POST['IQN'])) {
                require '../views/targets/delete/input.html';
            } else {
                preg_match_all("/path:(.*)/", $volumes, $a_paths);
                $IQN = $_POST['IQN'] - 1 ;
                $TID = $a_tid[1][$IQN];
                $NAME = $a_name[1][$IQN];
                $PATH = $a_paths[1][$IQN];

                //$key = array_search($TID, $a_tid[1]);

                exec("{$a_config['misc']['sudo']} {$a_config['iet']['ietadm']} --op delete --tid=$TID 2>&1", $status, $result);

                // Check if target is deleted from daemon and then delete it from config file as well
                if ($result ==! 0) {
                    throw new Exception("Error - Could not delete target $NAME. Server said: $status[0]");
                } else {
                    // Delete permission for target
                    deleteLineInFile($a_config['iet']['ietd_init_allow'], "$NAME");

                    // Delete target from config file
                    deleteLineInFile($a_config['iet']['ietd_config_file'], $NAME);
                    deleteLineInFile($a_config['iet']['ietd_config_file'], $PATH);
                    require '../views/targets/delete/success.html';
                }
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../views/error.html';
    }
    require '../views/div.html';
    require '../views/footer.html';
?>