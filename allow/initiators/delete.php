<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/initiators/delete/header.html';
    require '../../views/allow/menu.html';

    try {
        if (file_exists($proc_volumes)) {
            $a_volumes = get_file_cat($proc_volumes);
            if ($a_volumes == "error") {
                throw new Exception("Error - No targets found");
            } else {
                $name = get_data_regex($a_volumes, "/name:(.*)/");
                require '../../views/allow/initiators/delete/input.html';
            }
        } else {
            throw new Exception("Error - The file $proc_volumes was not found");
        }

        if (!empty($_POST['IQNs2'])) {
            $d=$_POST['IQNs2'];
            $d = $d-1;
            deleteLineInFile($ietd_init_allow, "$name[$d]");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }

    require '../../views/div.html';
    require '../../views/footer.html';
?>