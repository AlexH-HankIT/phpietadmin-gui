<?php
    class Dashboard extends Controller {
        /**
         *
         * Display the dashboard page
         *
         * @return      void
         *
         */
        public function index() {
            $data = $this->std->get_dashboard_data();
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
            echo file_get_contents(__DIR__ . '/../../version.json');
        }
    }