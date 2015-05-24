<?php
    class Objects {
        public function delete_object_from_iqn($iqn, $stringtodelete, $file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                foreach ($data as $key => $value) {
                    if (strpos($value, '#') !== false) {
                        true;
                    } else {
                        if (strpos($value, $iqn) !== false) {
                            $strtodeletepo = strpos($value, $stringtodelete);
                            if ($strtodeletepo !== false) {
                                $strtodeletelen = strlen($stringtodelete);

                                // If the $stringtodelete isn't the last, we have to delete a space and a comma after the string ended
                                if ($value[$strtodeletepo + $strtodeletelen] == ',') {
                                    $temp = substr_replace($value, '', $strtodeletepo, $strtodeletelen + 2);
                                    $data[$key] = $temp;
                                } else {
                                    // If the string is the last, we have to remove the previous space and comma
                                    $temp = substr_replace($value, '', $strtodeletepo - 2, $strtodeletelen + 2);
                                    $data[$key] = $temp;
                                }

                                // If iqn has the same length than value, there is only the iqn in this line
                                // therefore we just delete it
                                if (strlen($iqn) == strlen($temp)) {
                                    unset($data[$key]);
                                }
                            }
                        }
                    }
                }

                // Insert a newline at the end to prevent some issues
                if (end($data) !== "\n") {
                    array_push($data, "\n");
                }

                // Create string and write back
                $data = implode($data);
                file_put_contents($file, $data);

                return 0;
            }
        }

        public function add_object_to_iqn() {

        }
    }
?>