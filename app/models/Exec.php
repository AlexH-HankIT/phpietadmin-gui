<?php
    class Exec {
        private $sudo;
        private $ietadm;
        private $service;
        private $lvcreate;
        private $lvremove;

        public function __construct() {
            require_once 'Database.php';
            $database = new Database();

            $this->sudo = $database->get_config('sudo');
            $this->ietadm = $database->get_config('ietadm');
            $this->service = $database->get_config('service');
            $this->lvcreate = $database->get_config('lvcreate');
            $this->lvremove = $database->get_config('lvremove');
        }

        private function exec_and_return($command) {
            $command = escapeshellcmd($command);
            exec($command . " 2>&1", $status, $result);

            if ($result != 0) {
                return $status;
            } else {
                return 0;
            }
        }

        public function delete_target_from_daemon($tid) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . " --op delete --tid=" . $tid);
        }

        public function add_target_to_daemon($targetname) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op new --tid=0 --params Name=' . $targetname);
        }

        public function add_lun_to_daemon($tid, $lun, $path, $type, $mode) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . " --op new --tid=" . $tid . " --lun=" . $lun . " --params Path=" . $path . ",Type=" . $type . ",IOMode=" . $mode);
        }

        public function delete_lun_from_daemon($tid, $lun) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . " --op delete --tid=" . $tid . " --lun=" . $lun);
        }

        public function add_config_to_daemon($tid, $option, $newvalue) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op update  --tid=' . $tid . '--params=' . $option . '=' . $newvalue);
        }

        public function get_service_status($servicename) {
            return $this->exec_and_return($this->sudo . ' ' . $this->service . ' ' . $servicename . " status");
        }

        public function add_logical_volume($size, $name, $vg) {
            return $this->exec_and_return($this->sudo . ' ' . $this->lvcreate . ' -L ' . $size . 'G -n' . $name . " " . $vg);
        }

        public function delete_logical_volume($lun) {
            return $this->exec_and_return($this->sudo . ' ' . $$this->lvcreate . ' -f ' . $lun);
        }

        public function add_user_to_daemon($tid, $type, $username, $password) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op new --tid=' . $tid . ' --user --params=' . $type . '=' . $username . ',Password=' . $password);
        }

        public function delete_user_from_daemon($tid, $type, $user) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op delete --tid=' . $tid . ' --user --params=' . $type . '=' . $user);
        }

        public function add_discovery_user_to_daemon($type, $username, $password) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op new --user --params=' . $type . '=' . $username . ',Password=' . $password);
        }

        public function delete_discovery_user_from_daemon($type, $username) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op delete  --user --params=' . $type . "=" . $username);
        }

        public function change_service_state($servicename, $state) {
            return $this->exec_and_return($this->sudo . ' ' . $this->service . ' ' . $servicename . ' ' . $state);
        }
    }

?>