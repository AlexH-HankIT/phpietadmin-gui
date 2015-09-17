<?php namespace phpietadmin\app\models\lvm\logic;

    class Parser extends Exec {
        /**
         *
         * Parse the output of pvs/vgs/lvs and return a associative array
         *
         * @param    string  $type  output which should be parsed pv/vg/lv
         * @param    bool   $snaps if $type = 'lv', true = parse snapshots | false = don't parse snapshots
         * @return   int|array
         *
         */
        protected function parse_lvm($type, $snaps = false) {
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
                $heading = explode('*', $data[0]);
                unset($data[0]);

                $data = array_values($data);

                array_walk($data, function(&$value) {
                    $value = explode('*', $value);
                });

                // create associative array
                foreach ($data as $key => $row) {
                    foreach ($row as $rowkey => $property) {
                        // strip the g and replace the comma with a dot
                        // otherwise we can't calculate with these values
                        if ($heading[$rowkey] == 'VSize' || $heading[$rowkey] == 'VFree' || $heading[$rowkey] == 'LSize' || $heading[$rowkey] == 'PSize' || $heading[$rowkey] == 'PFree' || $heading[$rowkey] === 'Data%') {
                            $volumegroups[$key][$heading[$rowkey]] = str_replace(',', '.', str_replace('g', '', $property));
                        } else {
                            $volumegroups[$key][$heading[$rowkey]] = $property;
                        }

                        unset($volumegroups[$key][$rowkey]);
                    }
                }

                if (empty($volumegroups)) {
                    return false;
                } else {
                    return $volumegroups;
                }
            }
        }
    }