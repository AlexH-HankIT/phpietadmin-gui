<?php
    require 'views/header.html';
    require 'views/nav.html';
    require 'views/sessions/header.html';
    require 'views/overview/menu.html';

    try {
        if (file_exists($proc_sessions)) {
            $a_sessions = get_file_cat($proc_sessions);
            for ($i = 0; $i < count($a_sessions); $i++) {
                if (strpos($a_sessions[$i], 'cid') !== false) {
                    // Get array with tid
                    preg_match("/tid:([0-9].*?)/", $a_sessions[$i - 2], $result);
                    if (isset($result[1])) {
                        $tid_sessions[$i] = $result[1];
                        $tid_sessions = array_slice($tid_sessions, 0);
                    }

                    // Get array with names
                    preg_match("/name:(.*)/", $a_sessions[$i - 2], $result);
                    if (isset($result[1])) {
                        $name_sessions[$i] = $result[1];
                        $name_sessions = array_slice($name_sessions, 0);
                    }

                    // Get array with initiators
                    preg_match("/initiator:(.*)/", $a_sessions[$i - 1], $result);
                    if (isset($result[1])) {
                        $initiator[$i] = $result[1];
                        $initiator = array_slice($initiator, 0);
                    }

                    // Get array with ips
                    preg_match("/ip:(.*?) /", $a_sessions[$i], $result);
                    if (isset($result[1])) {
                        $ip[$i] = $result[1];
                        $ip = array_slice($ip, 0);
                    }

                    // Get array with state
                    preg_match("/state:(.*?) /", $a_sessions[$i], $result);
                    if (isset($result[1])) {
                        $state_sessions[$i] = $result[1];
                        $state_sessions = array_slice($state_sessions, 0);
                    }
                }
            }
            if (empty($tid_sessions)) {
                throw new Exception("Error - Could not create list of sessions");
            } else {
                require 'views/sessions/output.html';
            }
        } else {
            throw new Exception("Error - The file $proc_sessions was not found");
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require 'views/error.html';
    }

        require 'views/div.html';
        require 'views/footer.html';
?>