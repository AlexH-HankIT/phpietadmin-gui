<?php namespace phpietadmin\app\models\lvm\vg;
    use phpietadmin\app\models,
        phpietadmin\app\models\lvm\logic\Parser;

    /**
     * Vg class
     *
     * Contains methods to parse the properties of a volume group or logical volumes
     *
     * @package Vg
     * @author  Alexander Hank <mail@alexander-hank.de>
     *
     */
    class Vg extends Parser {
        /**
         * Returns all properties of $this->vg_name
         *
         * @param boolean $vg_name name of a volume group, if set to false, no volume group specific data will be returned
         *
         */
        public function __construct($vg_name = false) {
            // make database object available for Exec class
            $this->set_database(new models\Database());
            // make vg name globally available
            $this->set_vg_name($vg_name);
            parent::__construct();
        }
        /**
         * Returns all properties of $this->vg_name
         *
         * @return  array, boolean
         *
         */
        public function get_vg() {
            $this->log_action_result('The data of the volume group ' . $this->vg_name . ' was successfully parsed', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);
            $return = $this->parse_lvm('vg');
            if ($return === false) {
                $this->log_action_result('The volume group ' . $this->vg_name . ' does not exist or another error occured!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                return false;
            } else {
                return $return;
            }
        }
        /**
         * Returns all logical volumes of $this->vg_name
         *
         * @param $snap boolean, string $snap = true, return only snapshots, $snap = false return only non-snapshot volumes, $snap = '' return everything
         *
         * @return  array, boolean
         *
         */
        public function get_all_lv_from_vg($snap = false) {
            $this->log_action_result('The logical volumes of the volume group ' . $this->vg_name . ' were successfully parsed ', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);
            $return = $this->parse_lvm('lv');
            if ($return === false) {
                $this->log_action_result('The logical volumes of the volume group ' . $this->vg_name . ' were not parsed', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                return false;
            } else {
                if (empty($return)) {
                    $this->log_action_result('The volume group ' . $this->vg_name . ' has no logical volumes!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                } else {
                    if ($snap === false) {
                        foreach ($return as $key => $volume) {
                            if (!empty($volume['Origin'])) {
                                unset($return[$key]);
                            }
                        }
                    } else if ($snap === true) {
                        foreach ($return as $key => $volume) {
                            if (empty($volume['Origin'])) {
                                unset($return[$key]);
                            }
                        }
                    }
                }
                if (empty($return)) {
                    $this->log_action_result('The volume group ' . $this->vg_name . ' has no logical volumes!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                    return false;
                } else {
                    return array_values($return);
                }
            }
        }
    }