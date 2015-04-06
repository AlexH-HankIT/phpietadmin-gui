<?php
    require 'views/header.html';
    require 'views/nav.html';
    print_title("Volumes");
    require 'views/overview/menu.html';

    try {
        // Check if service is running and abort if not
        check_service_status();

        // Read contents of file $proc_volumes in var
        $volumes = file_get_contents($a_config['iet']['proc_volumes']);

        if (empty($volumes)) {
            throw new exception("Error - Could not create list of volumes");
        }

        for ($b=0; $b < substr_count($volumes, "\n")/2; $b++) {
            preg_match_all("/name:(.*)/", $volumes, $result);
            $data[$b][0] = $result[1][$b];

            preg_match_all("/tid:([0-9].*?) /", $volumes, $result);
            $data[$b][1] = $result[1][$b];

            preg_match_all("/path:(.*)/", $volumes, $result);
            $data[$b][2] = $result[1][$b];

            preg_match_all("/lun:([0-9].*?)/", $volumes, $result);
            $data[$b][3] = $result[1][$b];

            preg_match_all("/state:([0-9].*?)/", $volumes, $result);
            $data[$b][4] = $result[1][$b];

            preg_match_all("/iotype:([a-z].*?) /", $volumes, $result);
            $data[$b][5] = $result[1][$b];

            preg_match_all("/blocks:([0-9].*?) /", $volumes, $result);
            $data[$b][6] = $result[1][$b];

            preg_match_all("/blocksize:([0-9].*?) /", $volumes, $result);
            $data[$b][7] = $result[1][$b];
        }

        require 'views/volumes/output.html';
    }  catch (Exception $e) {
        print_error($e);
    }

    require 'views/footer.html';
?>