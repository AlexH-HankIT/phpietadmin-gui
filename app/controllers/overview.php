<?php
    class Overview extends Controller {
        public function disks() {
            $data = $this->disks->get_disks();

            if (!empty($data)) {
                $this->view('table', $data);
            } else {
                $this->view('message', 'Error - No block devices available!');
            }
        }

        public function ietvolumes() {
            $volumes = $this->ietvolumes->getIetVolumes();

            if ($volumes === 1 or $volumes === 2) {
                $this->view('message', "Error - The ietvolumes file was not found or is empty!");
            } else {
                $this->view('ietvolumes', $volumes);
            }
        }

        public function ietsessions() {
            $sessions = $this->ietsessions->getIetSessions();

            if (isset($_POST['tid'], $_POST['cid'], $_POST['sid'])) {
                $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . ' --op delete --tid=' . $_POST['tid'] . ' --sid=' . $_POST['sid'] . ' --cid=' . $_POST['cid']);
            }

            if ($sessions == 2 or $sessions == 1) {
                $this->view('message', "Error - The ietsessions file was not found or is empty!");
            } else {
                $this->view('ietsessions', $sessions);
            }
        }

        public function pv() {
            $data = $this->lvm->get_lvm_data('pvs');

            if ($data == 3 ) {
                $this->view('message', "Error - No physical volumes found!");
            } else {
                 $this->view('table', $data);
            }
        }

        public function vg() {
            $data = $this->lvm->get_lvm_data('vgs');

            if ($data == 3 ) {
                $this->view('message', "Error - No volume groups found!");
            } else {
                //$this->view('vgtable', $data);
                $this->view('table', $data);
            }
        }

        public function lv() {
            $data = $this->lvm->get_all_logical_volumes();

            if ($data == 3 ) {
                $this->view('message', "Error - No logical volumes found!");
            } else {
                $this->view('table', $data);
            }
        }
    }