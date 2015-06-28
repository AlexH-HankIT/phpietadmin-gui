<?php
    class Dashboard extends Controller {
        public function index() {
            $data = $this->std->get_dashboard_data();
            $this->view('dashboard', $data);
        }

        public function get_version() {
            echo file_get_contents('https://raw.githubusercontent.com/MrCrankHank/phpietadmin/master/version');
        }
    }
?>