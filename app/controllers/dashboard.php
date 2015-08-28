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
         * FROM GITHUB, not the local file
         *
         * ToDo: Fix this
         *
         * @return      void
         *
         */

        public function get_version() {
            echo file_get_contents(__DIR__ . '/../../version.json');
        }
    }