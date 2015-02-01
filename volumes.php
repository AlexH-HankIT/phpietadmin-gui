<?php
    require 'views/header.html';
    require 'views/nav.html';
    require 'views/volumes/header.html';
    require 'views/overview/menu.html';

    try {
        if (file_exists($proc_volumes)) {
            $a_volumes = get_file_cat($proc_volumes);
            if ($a_volumes == "error") {
                throw new Exception("Error - Could not create list of volumes");
            } else {
                $name = get_data_regex($a_volumes, "/name:(.*)/");
                $tid = get_data_regex($a_volumes, "/tid:([0-9].*?)/");
                $paths = get_data_regex($a_volumes, "/path:(.*)/");
                $lun = get_data_regex($a_volumes, "/lun:([0-9].*?)/");
                $state = get_data_regex($a_volumes, "/state:([0-9].*?)/");
                $type = get_data_regex($a_volumes, "/iotype:([a-z].*?) /");
                $blocks = get_data_regex($a_volumes, "/blocks:([0-9].*?) /");
                $size = get_data_regex($a_volumes, "/blocksize:([0-9].*?) /");

                require 'views/volumes/output.html';
            }
        } else {
            throw new Exception("Error - The file $proc_volumes was not found");
        }
    }  catch (Exception $e) {
        $error = $e->getMessage();
        require 'views/error.html';
    }

    require 'views/div.html';
    require 'views/footer.html';
?>