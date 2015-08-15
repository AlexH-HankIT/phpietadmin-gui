<?php namespace phpietadmin\app\models\lvm\pv;
    use phpietadmin\app\models\lvm\logic,
        phpietadmin\app\models;

    class Pv extends logic\Parser {
        public function __construct($pv_name = false) {
            $this->set_pv_name($pv_name);
            $this->set_database(new models\Database());
            parent::__construct();
        }

        public function get_pv() {
            $this->set_result('The data of the physical volume ' . $this->pv_name . ' was successfully parsed', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            $return = $this->parse_lvm('pv');

            if ($return === false) {
                $this->set_result('The physical volume ' . $this->pv_name . ' does not exist or some other error occurred!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                return false;
            } else {
                return $return;
            }
        }
    }