<?php
    class Settings {
        public $std;

        public function __construct() {
            $this->create_models();
        }

        private function create_models() {
            // Create other needed models in this model
            require_once 'Std.php';
            $this->std = new Std();
        }
    }
?>