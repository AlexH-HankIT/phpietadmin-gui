<?php
    class Parser extends Exec {
        public function __construct() {
            parent::__construct();
        }

        /**
         *
         * Parse the output of pvs/vgs/lvs and return a associative array
         *
         * @param    boolean, string  $vg_name  name of a volume group, if false, all volume groups will be returned
         * @return   int, array
         *
         */
        protected function parse_lvm($type) {
            $data = $this->get_lvm_output($type);

            if ($data === false) {
                return false;
            } else {
                $data = str_replace(' ', '', $data);

                // delete warning messages from pvs/vgs/lvs output
                if (strpos($data[0],'WARNING:') !== false) {
                    unset($data[0]);
                    $data = array_values($data);
                }

                // get keys for associative array
                $heading = explode('.', $data[0]);
                unset($data[0]);
                $data = array_values($data);

                foreach ($data as $key => $row) {
                    $volumegroups[$key] = explode('.', $row);
                }

                // create associative array
                foreach ($volumegroups as $key => $row) {
                    foreach ($row as $rowkey => $property) {
                        // strip the g and replace the comma with a dot
                        // otherwise we can't calculate with these values
                        if ($heading[$rowkey] == 'VSize' || $heading[$rowkey] == 'VFree' || $heading[$rowkey] == 'LSize' || $heading[$rowkey] == 'PSize' || $heading[$rowkey] == 'PFree') {
                            $volumegroups[$key][$heading[$rowkey]] = str_replace(',', '.', str_replace('g', '', $property));
                        } else {
                            $volumegroups[$key][$heading[$rowkey]] = $property;
                        }

                        unset($volumegroups[$key][$rowkey]);
                    }
                }
                return $volumegroups;
            }
        }
    }