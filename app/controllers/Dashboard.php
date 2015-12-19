<?php
namespace app\controllers;

use app\core,
    app\models;

class Dashboard extends core\BaseController {
    /**
     *
     * Display the dashboard page
     *
     * @return      void
     *
     */
    public function index() {
        $data = models\Misc::get_dashboard_data();
        $this->view('dashboard', $data);
    }
}