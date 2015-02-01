<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/targets/add/header.html';
    require '../../views/allow/menu.html';

    try {
        if (file_exists($proc_volumes)) {
            $a_volumes = get_file_cat($proc_volumes);
            if ($a_volumes == "error") {
                throw new Exception("Error - No targets found");
            } else {
                $name = get_data_regex($a_volumes, "/name:(.*)/");
                require '../../views/allow/targets/add/input.html';
            }
        } else {
            throw new Exception("Error - The file $proc_volumes was not found");
        }

        if (!empty($_POST['IQNs'])) {
            $d = $_POST['IQNs'];
            $d = $d-1;
            $current = "\n$name[$d] $_POST[ip]\n";
            file_put_contents($ietd_target_allow, $current, FILE_APPEND | LOCK_EX);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }

    require '../../views/div.html';
    require '../../views/footer.html';
?>