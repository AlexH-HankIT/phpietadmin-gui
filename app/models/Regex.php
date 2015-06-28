<?php
    class Regex
    {
        /* --------------------------------------------------------------------------------------------------------------------------------------------

        Start // Regex functions

        -----------------------------------------------------------------------------------------------------------------------------------------------*/

        // Uses regex to extract all target names from a given string
        function get_all_names_from_string($string)
        {
            preg_match_all("/name:(.*)/", $string, $a_name);
            return $a_name[1];
        }

        // Uses regex to extract all tids from a given string
        function get_all_tids_from_string($string)
        {
            preg_match_all("/tid:([0-9].*?) /", $string, $a_tid);
            return $a_tid[1];
        }

        function get_all_paths_from_string($string)
        {
            preg_match_all("/path:(.*)/", $string, $paths);
            if (!empty($paths)) {
                return $paths[1];
            } else {
                return 3;
            }
        }

        function get_all_luns_from_string($string)
        {
            preg_match_all("/lun:([0-9].*)/", $string, $luns);
            return $luns[1];
        }

        function get_all_states_from_string($string)
        {
            preg_match_all("/state:([0-9].*)/", $string, $result);
            return $result[1];
        }

        function get_all_iotypes_from_string($string)
        {
            preg_match_all("/iotype:(.*)/", $string, $result);
            return $result[1];
        }

        function get_all_iomodes_from_string($string)
        {
            preg_match_all("/iomode:(.*)/", $string, $result);
            return $result[1];
        }

        function get_all_blocks_from_string($string)
        {
            preg_match_all("/iomode:(.*)/", $string, $result);
            return $result[1];
        }

        function get_all_blocksize_from_string($string)
        {
            preg_match("/blocksize:(.*)/", $string, $result);
            return $result[1];
        }

        function replace_newlines_with_space($array)
        {
            // Replace all newlines with spaces
            return trim(preg_replace('/\s\s+/', ' ', $array));
        }

        /* --------------------------------------------------------------------------------------------------------------------------------------------

       End // Regex functions

       -----------------------------------------------------------------------------------------------------------------------------------------------*/
    }
?>