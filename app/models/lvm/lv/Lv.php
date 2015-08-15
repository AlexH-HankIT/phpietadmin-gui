<?php namespace phpietadmin\app\models\lvm\lv;
    use phpietadmin\app\models,
        phpietadmin\app\models\lvm\vg;

    /* ToDo:
        lv_attr:
            get_permission()
            get_allocation_policy()
            get_state()
            get_volume_health()
            check_open()

        state:
            change_state()
            activate_lv()
            deactivate_lv()
    */

    class Lv extends Logic {
        // true means the logical volume does exists
        // false means the logical volume doesn't
        protected $lv_status;

        /**
         *
         *
         */
        public function __construct($vg_name, $lv_name = false) {
            $this->set_database(new models\Database());

            // make vg name globally available
            $this->set_lv_name($lv_name);
            $this->set_vg_name($vg_name);

            // Get paths for binaries in Exec class
            parent::__construct();

            // create volume group object
            if (!empty($vg_name) && $vg_name !== false) {
                $this->vg = new vg\Vg($vg_name);
            }

            // check if we deal with a new or existing logical volume
            $data = $this->get_lv();

            if ($data === false) {
                $this->lv_status = false;
            } else {
                $this->lv_status = true;
            }
        }

        /* Logical volume specific methods - start */
        /* These methods are only working, if the object was instantiated with $lv_name != false */
        public function add_lv($size) {
            $this->check();

            $this->set_result('The logical volume ' . $this->lv_name . ' was successfully added to the volume group ' . $this->vg_name, array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->check_vg_enough_space($size) === false) {
                $this->set_result('The volume group ' . $this->vg_name . ' has not enough space to create the logical volume ' . $this->lv_name, array('result' => 8, 'code_type' => 'intern'), __METHOD__);
            } else {
                if ($this->lv_status === false) {
                    $return = $this->add_logical_volume($size);

                    if ($return['result'] != 0) {
                        $this->set_result('The logical volume ' . $this->lv_name . ' was not added to the volume group ' . $this->vg_name, $return, __METHOD__);
                    } else {
                        $this->lv_status = true;
                    }
                } else {
                    $this->set_result('The logical volume ' . $this->lv_name . ' does already exist in the volume group ' . $this->vg_name, array('result' => 4, 'code_type' => 'intern'),  __METHOD__);
                }
            }
        }

        public function snapshot_lv($size) {
            $this->check();
            $this->set_result('The snapshot of the logical volume ' . $this->lv_name . ' was successfully created', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->lv_status === true) {
                if ($this->check_vg_enough_space($size) === false) {
                    $this->set_result('The volume group ' . $this->vg_name . ' has not enough space to create a ' . $size . ' snapshot of the logical volume ' . $this->lv_name, array('result' => 4, 'code_type' => 'intern'),  __METHOD__);
                } else {
                    $return = $this->create_lv_snapshot($size);
                    if ($return['result'] != 0) {
                        $this->set_result('The snapshot of the logical volume ' . $this->lv_name . ' was not created!', $return,  __METHOD__);
                    }
                }
            } else {
                $this->set_result('The logical volume ' . $this->lv_name . ' does not exist in the volume group ' . $this->vg_name, array('result' => 3, 'code_type' => 'intern'),  __METHOD__);
            }
        }

        // return snapshtos for $this->lv_name
        public function get_snapshot() {
            $this->check();
            $data = $this->return_snapshots();

            $this->set_result('The snapshots of the logical volume ' . $this->lv_name . ' were successfully fetched', array('result' => 0, 'code_type' => 'intern'),  __METHOD__, true);

            if ($data === false) {
                $this->set_result('The logical volume ' . $this->lv_name . ' has no snapshots! ', array('result' => 3, 'code_type' => 'intern'),  __METHOD__);
                return false;
            } else {
                return $data;
            }
        }

        public function remove_lv() {
            $this->check();

            $this->set_result('The logical volume ' . $this->lv_name . ' was successfully removed from the volume group ' . $this->vg_name, array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->lv_status === true) {
                $return = $this->delete_logical_volume();

                if ($return['result'] != 0) {
                    $this->set_result('The logical volume ' . $this->lv_name . ' was not removed from the volume group ' . $this->vg_name, $return, __METHOD__);
                }
            } else {
                $this->set_result('The logical volume ' . $this->lv_name . ' does not exist in the volume group ' . $this->vg_name, array('result' => 3, 'code_type' => 'intern'),  __METHOD__);
            }
        }

        public function rename_lv($new_lv_name) {
            $this->check();

            $this->set_result('The logical volume ' . $this->lv_name . ' was successfully renamed to ' . $new_lv_name, array('result' => 0, 'code_type' => 'intern'), true);

            if ($this->lv_status === false) {
                $this->set_result('The logical volume ' . $this->lv_name . ' does not exist!', array('result' => 3, 'code_type' => 'intern'),  __METHOD__, true);
            } else {
                if ($new_lv_name == $this->lv_name) {
                    $this->set_result('The new name is the same!', array('result' => 3, 'code_type' => 'intern'),  __METHOD__);
                } else {
                    $data = $this->get_all_volumes_from_this_vg();

                    $key = $this->recursive_array_search($new_lv_name, $data);

                    if ($key !== false) {
                        $this->set_result('A logical volume with the name ' . $new_lv_name . ' does already exist!', array('result' => 3, 'code_type' => 'intern'),  __METHOD__);
                    } else {
                        $return = $this->change_lv_name($new_lv_name);

                        if ($return['result'] != 0) {
                            $this->set_result('The logical volume was not renamed!', $return,  __METHOD__);
                        } else {
                            $this->lv_name = $new_lv_name;
                        }
                    }
                }
            }
        }

        /**
         * Extend $this->lv_name
         *
         * @param string $new_size target size of the volume, e.g. if you have a volume, which is 4g you have to specify 5g to add 1g
         *
         */
        public function extend_lv($new_size) {
            $this->check();

            $data = $this->get_lv();

            $extend_size = $new_size - $data[0]['LSize'];

            $this->set_result('The logical volume ' . $this->lv_name . ' was successfully extended to ' . $new_size . 'gb', array('result' => 0, 'code_type' => 'intern'),  __METHOD__, true);

            if ($extend_size <= 0) {
                $this->set_result('The size cannot be smaller or equal!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
            } else {
                if($this->check_vg_enough_space($extend_size) === false) {
                    $this->set_result('The volume group ' . $this->vg_name . ' has not enough space to extend the logical volume ' . $this->lv_name, array('result' => 8, 'code_type' => 'intern'), __METHOD__);
                } else {
                    if ($this->lv_status === true) {
                        $return = $this->extend_lv_size($new_size);

                        if ($return['result'] != 0) {
                            $this->set_result('The logical volume ' . $this->lv_name . ' was not extended to ' . $new_size . 'gb', $return,  __METHOD__);
                        }
                    } else {
                        $this->set_result('The logical volume ' . $this->lv_name . ' does not exist in the volume group ' . $this->vg_name, array('result' => 3, 'code_type' => 'intern'),  __METHOD__);
                    }
                }
            }
        }

        public function reduce_lv($new_size) {
            $this->check();

            $this->set_result('The logical volume ' . $this->lv_name . ' was successfully reduced to ' . $new_size . 'gb', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->lv_status === true) {
                $return = $this->reduce_lv_size($new_size);

                if ($return['result'] != 0) {
                    $this->set_result('The logical volume ' . $this->lv_name . ' was not reduced to ' . $new_size . 'gb', $return,  __METHOD__);
                }
            } else {
                $this->set_result('The logical volume ' . $this->lv_name . ' does not exist in the volume group ' . $this->vg_name, array('result' => 3, 'code_type' => 'intern'),  __METHOD__);
            }
        }

        public function merge_snapshot() {
            $this->check();

            $this->set_result('The snapshot ' . $this->lv_name . ' was successfully merged', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->is_snapshot() === true) {
                $return = $this->merge_lv_snapshot();

                if ($return['result'] != 0) {
                    $this->set_result('The snapshot ' . $this->lv_name . ' was not merged', $return,  __METHOD__);
                }
            } else {
                $this->set_result('The logical volume ' . $this->lv_name . ' is not a snapshot!', array('result' => 3, 'code_type' => 'intern'), __METHOD__, true);
            }
        }

        /* Logical volume specific methods - end */

        /* Methods which are working without a logical volume - start */
        // return array with all *normal* (non-snapshot) volumes
        public function get_lv() {
            if ($this->lv_name !== false) {
                $this->set_result('The data of the logical volume ' . $this->lv_name . ' was successfully parsed', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);
            } else {
                $this->set_result('The data of the logical volumes were successfully parsed', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);
            }

            $return = $this->parse_lvm('lv');

            if ($return === false) {
                $this->set_result('The logical volume ' . $this->lv_name . ' does not exist or another error occurred!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                return false;
            } else {
                return $return;
            }
        }

        /**
         * Uses the output of return_all_used_lun() to get all unused ones
         *
         * @param $used_lun array|bool output of $target->return_all_used_lun()
         * @return bool|array
         */
        public function get_unused_lun($used_lun) {
            $logical_volumes = $this->parse_lvm('lv');

            if (!empty($logical_volumes) && $logical_volumes !== false) {
                array_walk($logical_volumes, function(&$value) {
                    $value = '/dev/' . $value['VG'] . '/' . $value['LV'];
                });

                if (empty($logical_volumes)) {
                    return false;
                } else if ($used_lun === false) {
                    return $logical_volumes;
                } else {
                    return array_diff($logical_volumes, $used_lun);
                }
            } else {
                return false;
            }
        }
        /* Methods which are working without a logical volume - end  */
    }