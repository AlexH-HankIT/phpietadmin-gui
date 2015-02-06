<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/allow/initiators/delete/header.html';
    require '../../views/allow/menu.html';


    // Callback function for array_filter
    /*function contains_hashtag($var) {
        if (strpos($var, "#") === false) {
            return $var;
        }
    }*/

    try {
        // Check if service is running and abort if not
        check_service_status();

        $volumes = file_get_contents($proc_volumes);
        //preg_match_all("/name:(.*)/", file_get_contents($proc_volumes), $result);

        //$a_initiators = array_values(array_filter(explode("\n", file_get_contents($ietd_init_allow)), "contains_hashtag" ));
        if (empty($volumes)) {
            throw new Exception("Error - No targets found");
        } else {
            preg_match_all("/name:(.*)/", $volumes, $a_name);
            require '../../views/allow/initiators/delete/input.html';
        }

        /*echo "<pre>";
        print_r($result);
        print_r($a_initiators);
        for ($i=0; $i < count($name); $i++) {
            if (!empty($a_initiators[$i])) {
            if (strpos($name[$i], "$a_initiators[$i]") == false) {
               echo $name[$i] . "\n";
            }
            //echo "\n";
            //echo $name[$i];
            //echo "\n";
            //echo $a_initiators[$i];
            //echo "\n";
            }
        }
        echo "</pre>";*/

        if (!empty($_POST['IQNs2'])) {
            $d=$_POST['IQNs2'] -1;
            $NAME = $a_name[1][$d];
            deleteLineInFile($ietd_init_allow, "$NAME");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../../views/error.html';
    }

    require '../../views/div.html';
    require '../../views/footer.html';
?>