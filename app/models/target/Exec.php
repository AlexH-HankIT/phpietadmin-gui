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
            Logic::__construct();
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
            $this->log_debug_result($this->ietadm . ' --op delete --tid=' . $this->tid, __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid);
        }

        /**
         *
         * Add a target without daemon restart
         *
         * @return   array
         *
         */
        protected function add_target_to_daemon() {
            $this->log_debug_result($this->ietadm . ' --op new --tid=0 --params Name=' . $this->iqn, __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op new --tid=0 --params Name=' . $this->iqn);
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
            $this->log_debug_result($this->ietadm . ' --op new --tid=' . $this->tid . ' --lun=' . $lun . ' --params Path=' . $path . ',Type=' . $type . ',IOMode=' . $iomode, __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op new --tid=' . $this->tid . ' --lun=' . $lun . ' --params Path=' . $path . ',Type=' . $type . ',IOMode=' . $iomode);
        }

        protected function delete_logical_volume($lun) {
            $this->log_debug_result($this->lvremove . ' --force / ' . $lun, __METHOD__, 'exec()');
            return $this->exec_and_return($this->lvremove . ' --force / ' . $lun);
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
            $this->log_debug_result($this->ietadm . ' --op delete --tid=' . $this->tid . ' --lun=' . $id, __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid . ' --lun=' . $id);
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
            $this->log_debug_result($this->ietadm . ' --op update  --tid=' . $this->tid . '--params=' . $option . '=' . $newvalue, __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op update  --tid=' . $this->tid . '--params=' . $option . '=' . $newvalue);
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
                $this->log_debug_result($this->ietadm . ' --op show --user', __METHOD__, 'exec()');
                $return = $this->exec_and_return($this->ietadm . ' --op show --user');
            } else {
                $this->log_debug_result($this->ietadm . ' --op show --tid=' . $this->tid . ' --user', __METHOD__, 'exec()');
                $return = $this->exec_and_return($this->ietadm . ' --op show --tid=' . $this->tid . ' --user');
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
            $this->log_debug_result($this->ietadm . ' --op delete --tid=' . $this->tid . ' --sid=' . intval($sid) . ' --cid=' . intval($cid), __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid . ' --sid=' . intval($sid) . ' --cid=' . intval($cid));
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
            $this->log_debug_result($this->service . ' ' . $servicename . ' status', __METHOD__, 'exec()');
            return $this->exec_and_return($this->service . ' ' . $servicename . ' status');
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
            $this->log_debug_result($this->ietadm . ' --op new --tid=' . $this->tid . ' --user --params=' . $type . '=' . $username . ',Password=<password>', __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op new --tid=' . $this->tid . ' --user --params=' . $type . '=' . $username . ',Password=' . $password);
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
            $this->log_debug_result($this->ietadm . ' --op delete --tid=' . $this->tid . ' --user --params=' . $type . '=' . $user, __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op delete --tid=' . $this->tid . ' --user --params=' . $type . '=' . $user);
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
            $this->log_debug_result($this->ietadm . ' --op new --user --params=' . $type . '=' . $username . ',Password=<password>', __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op new --user --params=' . $type . '=' . $username . ',Password=' . $password);
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
            $this->log_debug_result($this->ietadm . ' --op delete  --user --params=' . $type . '=' . $username, __METHOD__, 'exec()');
            return $this->exec_and_return($this->ietadm . ' --op delete  --user --params=' . $type . '=' . $username);
        }
    }