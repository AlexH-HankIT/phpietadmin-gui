<?php namespace phpietadmin\app\models;
use phpietadmin\app\core;

class Config extends core\BaseModel {
    private $option;
    private $data;

    // if false config doesn't exist
    private $status;

    public function __construct($option) {
        // instantiate parent class to access database and logging functions
        parent::__construct();

        // make option global
        $this->option = $option;

        // check if option exists in database
        $this->exists();
    }

    private function exists() {
		$this->data = $this->database->get_config($this->option);

        if ($this->data === false) {
            $this->status = false;
        } else {
            $this->status = true;
        }
    }

    public function return_status() {
        return $this->status;
    }

    public function return_data() {
        if ($this->status === true) {
            return $this->data;
        } else {
            // error, no data to return
            $this->logging->log_action_result('The config option doesn\'t exist!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
            return false;
        }
    }

    /**
     *
     * @param $row string sql row you want to change
     * @param $value string the target value
     */
    public function change_config($row, $value) {
        if ($this->status === true) {
            if ($this->data['type'] === 'file' || $this->data['type'] === 'bin' || $this->data['type'] === 'subin') {
                if (file_exists($value)) {
                    $return = $this->database->update_config($this->option, $value, $row);
                    if ($return != 0) {
                        $this->logging->log_action_result('The file path ' . $this->data['value'] . ' was not added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
                    } else {
                        $this->logging->log_action_result('The file path ' . $this->data['value'] . ' was successfully added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
                    }
                } else {
                    $this->logging->log_action_result('The file ' . $this->data['value'] . ' was not found!', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
                }
            } else if ($this->data['type'] === 'folder') {
                if (is_dir($value)) {
                    $return = $this->database->update_config($this->option, $value, $row);
                    if ($return != 0) {
                        $this->logging->log_action_result('The folder directory ' . $this->data['value'] . ' was not added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
                    } else {
                        $this->logging->log_action_result('The folder directory ' . $this->data['value'] . ' was successfully added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
                    }
                } else {
                    $this->logging->log_action_result('The directory ' . $this->data['value'] . ' was not found!', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
                }
            } else {
                $return = $this->database->update_config($this->option, $value, $row);

                if ($return != 0) {
                    $this->logging->log_action_result('The value ' . $this->data['value'] . ' was not added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
                } else {
                    $this->logging->log_action_result('The value ' . $this->data['value'] . ' was successfully added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
                }
            }
        } else {
            $this->logging->log_action_result('The config option doesn\'t exist!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
        }
    }
}