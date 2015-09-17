<?php namespace phpietadmin\app\models\lvm\logic;
    use phpietadmin\app\core;

    class Exec extends core\BaseModel {
        private $lvcreate;
        private $lvremove;
        private $lvrename;
        private $lvextend;
        private $lvreduce;
        private $lvconvert;
        private $vgs;
        private $pvs;
        private $lvs;
        protected $vg_name;
        protected $pv_name;
        protected $lv_name;
        protected $vg;

        public function __construct() {
            parent::__construct();

            $this->lvcreate = $this->database->get_config('lvcreate')['value'];
            $this->lvremove = $this->database->get_config('lvremove')['value'];
            $this->vgs = $this->database->get_config('vgs')['value'];
            $this->pvs = $this->database->get_config('pvs')['value'];
            $this->lvs = $this->database->get_config('lvs')['value'];
            $this->lvrename = $this->database->get_config('lvrename')['value'];
            $this->lvextend = $this->database->get_config('lvextend')['value'];
            $this->lvreduce = $this->database->get_config('lvreduce')['value'];
            $this->lvconvert = $this->database->get_config('lvconvert')['value'];
        }

        protected function set_vg_name($vg_name) {
            $this->vg_name = $vg_name;
        }

        protected function set_pv_name($pv_name) {
            $this->pv_name = $pv_name;
        }

        protected function set_lv_name($lv_name) {
            $this->lv_name = $lv_name;
        }

        /**
         *
         * Add a logical volume
         *
         * @param    string  $size  size of the volume in gb
         * @return   array
         *
         */
        protected function add_logical_volume($size) {
            return $this->std->exec_and_return($this->lvcreate . ' -L ' . $size . 'g -n' . $this->lv_name . ' ' . $this->vg_name);
        }

        /**
         *
         * Delete a logical volume
         *
         * @return   array
         *
         */
        protected function delete_logical_volume() {
            return $this->std->exec_and_return($this->lvremove . ' --force /' . '/dev/' . $this->vg_name . '/' . $this->lv_name);
        }

        protected function create_lv_snapshot($size) {
            return $this->std->exec_and_return($this->lvcreate . ' --snapshot --size ' . $size . 'g --name ' . '/dev/' . $this->vg_name . '/' . $this->lv_name . '_snapshot_' . time() . ' /dev/' .$this->vg_name . '/' . $this->lv_name);
        }

        protected function change_lv_name($new_name) {
            return $this->std->exec_and_return($this->lvrename . ' /dev/' . $this->vg_name . '/' . $this->lv_name . ' /dev/' . $this->vg_name . '/' . $new_name);
        }

        protected function extend_lv_size($size) {
            return $this->std->exec_and_return($this->lvextend . ' --force -L' . $size . 'G' . ' /dev/' . $this->vg_name . '/' . $this->lv_name);
        }

        protected function reduce_lv_size($size) {
            return $this->std->exec_and_return($this->lvreduce . ' --force -L' . $size . 'G' . ' /dev/' . $this->vg_name . '/' . $this->lv_name);
        }

        protected function merge_lv_snapshot() {
            return $this->std->exec_and_return($this->lvconvert . ' --merge /dev/' . $this->vg_name . '/' . $this->lv_name);
        }

        /**
         *
         * Executes pvs/vgs/lvs and return the output as string
         *
         * @param    boolean, string  $vg_name  name of a volume group, if false, all volume groups will be returned
         * @return   boolean, array
         *
         */
        protected function get_lvm_output($type) {
            if ($type == 'vg') {
                if ($this->vg_name !== false) {
                    exec($this->vgs . ' --units g --separator "*" ' . escapeshellarg($this->vg_name) . ' 2>&1', $data, $result);
                } else {
                    exec($this->vgs . ' --units g --separator "*" ' . ' 2>&1', $data, $result);
                }
            } else if ($type == 'pv') {
                if ($this->pv_name !== false) {
                    exec($this->pvs . ' --units g --separator "*" ' . escapeshellarg($this->pv_name) . ' 2>&1', $data, $result);
                } else {
                    exec($this->pvs . ' --units g --separator "*"' . ' 2>&1', $data, $result);
                }
            } else {
                if (!empty($this->lv_name) && $this->lv_name !== false) {
                    exec($this->lvs . ' --units g --separator "*" /dev/' . escapeshellarg($this->vg_name) . '/' . escapeshellarg($this->lv_name) . ' 2>&1', $data, $result);
                } else if ($this->vg_name !== false) {
                    exec($this->lvs . ' --units g --separator "*" /dev/' . escapeshellarg($this->vg_name) . ' 2>&1', $data, $result);
                } else {
                    exec($this->lvs . ' --units g --separator "*"' . ' 2>&1', $data, $result);
                }
            }

            if (empty($data)) {
                return false;
            } else {
                if ($result != 0) {
                    return false;
                } else {
                    return $data;
                }
            }
        }
    }