<?php namespace phpietadmin\app\controllers;
	use phpietadmin\app\core;

    class Dashboard extends core\BaseController {
        /**
         *
         * Display the dashboard page
         *
         * @return      void
         *
         */
        public function index() {
            $data = $this->base_model->std->get_dashboard_data();
            $this->view('dashboard', $data);
        }

        /**
         *
         * Get the up2date phpietadmin version from github
         *
         * @return      void
         *
         */

        public function get_version() {
            echo file_get_contents('https://raw.githubusercontent.com/MrCrankHank/phpietadmin/master/version.json');
        }
    }