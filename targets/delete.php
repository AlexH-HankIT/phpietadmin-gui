<?php
    require '../views/header.html';
    require '../views/nav.html';
    require '../views/targets/delete/header.html';
    require '../views/targets/menu.html';

    try {
        if (file_exists($proc_volumes)) {
            $a_volumes = get_file_cat($proc_volumes);
            if ($a_volumes == "error") {
                throw new Exception("Error - No targets found");
            } else {
                $a_name = get_data_regex($a_volumes, "/name:(.*)/");
                $a_tid = get_data_regex($a_volumes, "/tid:([0-9].*?)/");

                if (!isset($_POST['IQN'])) {
                    require '../views/targets/delete/input.html';
                } else {
                    $IQN = $_POST['IQN'] - 1 ;
                    $TID = $a_tid[$IQN];
                    $NAME = $a_name[$IQN];
                    $a_paths = get_data_regex($a_volumes, "/path:(.*)/");
                    $key = array_search("$TID", $a_tid );

                    exec("$sudo $ietadm --op delete --tid=$TID 2>&1", $status, $result);

                    // Check if target is deleted from daemon and then delete it from config file as well
                    if ($result ==! 0) {
                        throw new Exception("Error - Could not delete target $NAME. Server said: $status[0]");
                    } else {
                        deleteLineInFile("$ietd_config_file", "$a_name[$key]");
                        deleteLineInFile("$ietd_config_file", "$a_paths[$key]");
                        require '../views/targets/delete/success.html';
                    }
                }
            }
        } else {
            throw new Exception("Error - The file $proc_volumes was not found");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../views/error.html';
    }
    require '../views/div.html';
    require '../views/footer.html';
?>