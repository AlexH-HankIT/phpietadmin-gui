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
            $data = $this->baseModel->std->get_dashboard_data();
            $this->view('dashboard', $data);
        }
    }