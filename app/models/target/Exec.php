<?php namespace phpietadmin\app\models\target;
    /**
     * Exec class with all cli commands
     *
     * @package Exec
     * @author     Alexander Hank <mail@alexander-hank.de>
     *
     */
    class Exec extends Logic {
        private $ietadm;
        private $service;
        private $lvremove;

        /**
         *
         * Create a database object and get the paths to the binaries
         *
         */
        public function __construct() {
            parent::__construct();
            $this->ietadm = $this->database->get_config('ietadm')['value'];
            $this->service = $this->database->get_config('service')['value'];
            $this->lvremove = $this->database->get_config('lvremove')['value'];
        }

        /**
         *
         * Delete a target without daemon restart
         *
         * @return   array
         *
         */
        protected function delete_target_from_daemon() {
            return $this->std->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid);
        }

        /**
         *
         * Add a target without daemon restart
         *
         * @return   array
         *
         */
        protected function add_target_to_daemon() {
            return $this->std->exec_and_return($this->ietadm . ' --op new --tid=0 --params Name=' . $this->iqn);
        }

        /**
         *
         * Add a lun to a target without daemon restart
         *
         * @param   int     $lun   next available lun number of the target
         * @param   string  $path  path to the block device, which is used as lun
         * @param   string  $type  wt|ro
         * @param   string  $iomode  fileio|blockio
         * @return  array
         *
         */
        protected function add_lun_to_daemon($lun, $path, $iomode, $type) {
           return $this->std->exec_and_return($this->ietadm . ' --op new --tid=' . $this->tid . ' --lun=' . $lun . ' --params Path=' . $path . ',Type=' . $type . ',IOMode=' . $iomode);
        }

        protected function delete_logical_volume($lun) {
            return $this->std->exec_and_return($this->lvremove . ' --force ' . $lun);
        }

        /**
         *
         * Delete a lun from a target without daemon restart
         *
         * @param    int  $id  id of the lun
         * @return   array
         *
         */
        protected function delete_lun_from_daemon($id) {
            return $this->std->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid . ' --lun=' . $id);
        }

        /**
         *
         * Add a option without daemon restart
         *
         * @param    string  $option    option which should be added to the daemon
         * @param    string  $newvalue  value of the option
         * @return   array
         *
         */
        protected function add_config_to_daemon($option, $newvalue) {
            return $this->std->exec_and_return($this->ietadm . ' --op update  --tid=' . $this->tid . '--params=' . $option . '=' . $newvalue);
        }

        /**
         *
         * This function returns all users for $this->iqn or all discovery users
         *
         * @param    boolean  $discovery    if set to true, all discovery users will be returned
         * @return   int
         *
         */
        protected function get_configured_iet_users($discovery = false) {
            if ($discovery === true) {
                $return = $this->std->exec_and_return($this->ietadm . ' --op show --user');
            } else {
                $return = $this->std->exec_and_return($this->ietadm . ' --op show --tid=' . $this->tid . ' --user');
            }

            if (!empty($return['status'])) {
                foreach ($return['status'] as $value) {
                    $user[] = explode(' ', $value);
                }

                return $user;
            } else {
                return 3;
            }
        }

        /**
         *
         * Disconnects a initiator session
         * Most initiators reconnect immediately
         * You can avoid this, by removing the matching initiator allow rule first
         *
         * @param    int     $sid       session id of the target
         * @param    int  $cid    ??
         * @return   array
         *
         */
        protected function exec_disconnect_session($sid, $cid) {
            return $this->std->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid . ' --sid=' . intval($sid) . ' --cid=' . intval($cid));
        }

        /**
         *
         * Get the status of a service
         *
         * @param    string  $servicename  name of the service
         * @return   array
         *
         */
        protected function get_service_status($servicename) {
            return $this->std->exec_and_return($this->service . ' ' . $servicename . ' status');
        }

        /**
         *
         * Add a iet user without daemon restart
         *
         * @param    string  $type incoming/outgoing
         * @param    string  $username name of the user
         * @param    string  $password password of the user
         * @return   array
         *
         */
        protected function add_user_to_daemon($type, $username, $password) {
            return $this->std->exec_and_return($this->ietadm . ' --op new --tid=' . $this->tid . ' --user --params=' . $type . '=' . $username . ',Password=' . $password);
        }

        /**
         *
         * Delete a iet user without daemon restart
         *
         * @param    string  $type incoming/outgoing
         * @param    string  $user name of the user
         * @return   array
         *
         */
        protected function delete_user_from_daemon($type, $user) {
            return $this->std->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid . ' --user --params=' . $type . '=' . $user);
        }

        /**
         *
         * Add a discovery user without daemon restart
         *
         * @param    string  $type incoming/outgoing
         * @param    string  $username name of the user
         * @param    string  $password password of the user
         * @return   array
         *
         */
        protected function add_discovery_user_to_daemon($type, $username, $password) {
            return $this->std->exec_and_return($this->ietadm . ' --op new --user --params=' . $type . '=' . $username . ',Password=' . $password);
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
        protected function delete_discovery_user_from_daemon($type, $username) {
            return $this->std->exec_and_return($this->ietadm . ' --op delete  --user --params=' . $type . '=' . $username);
        }
    }