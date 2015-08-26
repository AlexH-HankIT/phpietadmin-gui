<?php
    /**
     * Exec class with all cli commands
     *
     * @package Exec
     * @author     Alexander Hank <mail@alexander-hank.de>
     *
     */
    class Exec {
        private $sudo;
        private $ietadm;
        private $service;
        private $lvcreate;
        private $lvremove;

        /**
         *
         * Create a database object and get the paths to the binaries
         *
         * @param   object $models database connection
         *
         */
        public function __construct($models) {
            $this->sudo = $models['database']->get_config('sudo');
            $this->ietadm = $models['database']->get_config('ietadm');
            $this->service = $models['database']->get_config('service');
            $this->lvcreate = $models['database']->get_config('lvcreate');
            $this->lvremove = $models['database']->get_config('lvremove');
        }

        /**
         *
         * Escape and execute a command
         *
         * @param    string  $command  command to be executed
         * @return      int
         *
         */
        private function exec_and_return($command) {
            $command = escapeshellcmd($command);
            exec($command . ' 2>&1', $status, $result);

            if ($result != 0) {
                return $status;
            } else {
                return 0;
            }
        }

        /**
         *
         * Delete a target without daemon restart
         *
         * @param    int  $tid  tid of the target which should be deleted
         * @return   int
         *
         */
        public function delete_target_from_daemon($tid) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op delete --tid=' . $tid);
        }

        /**
         *
         * Add a target without daemon restart
         *
         * @param    string  $targetname  name of the new target
         * @return   int
         *
         */
        public function add_target_to_daemon($targetname) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op new --tid=0 --params Name=' . $targetname);
        }

        /**
         *
         * Add a lun to a target without daemon restart
         *
         * @param   int     $tid   tid of the target
         * @param   int     $lun   next available lun number of the target
         * @param   string  $path  path to the block device, which is used as lun
         * @param   string  $type  fileio/blockio
         * @param   string  $mode  wt/ro
         * @return  int
         *
         */
        public function add_lun_to_daemon($tid, $lun, $path, $type, $mode) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op new --tid=' . $tid . ' --lun=' . $lun . ' --params Path=' . $path . ',Type=' . $type . ',IOMode=' . $mode);
        }

        /**
         *
         * Delete a lun from a target without daemon restart
         *
         * @param    int  $tid  tid of the target
         * @param    int  $lun  lun number of the lun, which should be deleted
         * @return   int
         *
         */
        public function delete_lun_from_daemon($tid, $lun) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op delete --tid=' . $tid . ' --lun=' . $lun);
        }

        /**
         *
         * Add a option without daemon restart
         *
         * @param    int     $tid       tid of the target
         * @param    string  $option    option which should be added to the daemon
         * @param    string  $newvalue  value of the option
         * @return   int
         *
         */
        public function add_config_to_daemon($tid, $option, $newvalue) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op update  --tid=' . $tid . '--params=' . $option . '=' . $newvalue);
        }

        /**
         *
         * Get the status of a service
         *
         * @param    string  $servicename  name of the service
         * @return   int
         *
         */
        public function get_service_status($servicename) {
            return $this->exec_and_return($this->sudo . ' ' . $this->service . ' ' . $servicename . ' status');
        }

        /**
         *
         * Add a logical volume
         *
         * @param    string  $size  size of the volume
         * @param    string  $name  name of the volume
         * @param    string  $vg    name of the volume group
         * @return   int
         *
         */
        public function add_logical_volume($size, $name, $vg) {
            return $this->exec_and_return($this->sudo . ' ' . $this->lvcreate . ' -L ' . $size . 'G -n' . $name . " " . $vg);
        }

        /**
         *
         * Delete a logical volume
         *
         * @param    string  $lv  path to the logical volume
         * @return   int
         *
         */
        public function delete_logical_volume($lv) {
            return $this->exec_and_return($this->sudo . ' ' . $this->lvcreate . ' -f ' . $lv);
        }

        /**
         *
         * Add a ietd user without daemon restart
         *
         * @param    int  $tid   tid of the target
         * @param    string  $type incoming/outgoing
         * @param    string  $username name of the user
         * @param    string  $password password of the user
         * @return   int
         *
         */
        public function add_user_to_daemon($tid, $type, $username, $password) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op new --tid=' . $tid . ' --user --params=' . $type . '=' . $username . ',Password=' . $password);
        }

        /**
         *
         * Delete a ietd user without daemon restart
         *
         * @param    int  $tid   tid of the target
         * @param    string  $type incoming/outgoing
         * @param    string  $user name of the user
         * @return   int
         *
         */
        public function delete_user_from_daemon($tid, $type, $user) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op delete --tid=' . $tid . ' --user --params=' . $type . '=' . $user);
        }

        /**
         *
         * Add a discovery user without daemon restart
         *
         * @param    string  $type incoming/outgoing
         * @param    string  $username name of the user
         * @param    string  $password password of the user
         * @return   int
         *
         */
        public function add_discovery_user_to_daemon($type, $username, $password) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op new --user --params=' . $type . '=' . $username . ',Password=' . $password);
        }

        /**
         *
         * Delete a discovery user without daemon restart
         *
         * @param    string  $type incoming/outgoing
         * @param    string  $username name of the user
         * @return   int
         *
         */
        public function delete_discovery_user_from_daemon($type, $username) {
            return $this->exec_and_return($this->sudo . ' ' . $this->ietadm . ' --op delete  --user --params=' . $type . '=' . $username);
        }

        /**
         *
         * Start/stop/restart a service
         *
         * @param    string  $servicename service to started/stoped/restarted
         * @param    string  $state start/stop/restart
         * @return   int
         *
         */
        public function change_service_state($servicename, $state) {
            return $this->exec_and_return($this->sudo . ' ' . $this->service . ' ' . $servicename . ' ' . $state);
        }
    }