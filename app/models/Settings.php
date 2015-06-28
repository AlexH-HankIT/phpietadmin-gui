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

        public function delete_unnecessary_settings($data) {
            /* Everything with three or more options is a lun or user, both are handled in other parts of this software */
            foreach ($data as $key => $value) {
                if (count($value) >= 3) {
                    unset($data[$key]);
                }
            }

            // Correct index
            return array_values($data);
        }

        public function get_values_for_options($options, $settings) {
            // this function gets the actual value of the option
            // if it is not set, the default value will be used
            // Get values for every option
            foreach ($options as $option) {
                $key = $this->std->recursive_array_search($option['option'], $settings);

                if (is_int($key)) {
                    $values[$option['option']] = $settings[$key][1];
                } else {
                    $values[$option['option']] = $option['default'] . "\n";
                }
            }

            $merged['values'] = $values;
            $merged['options'] = $options;

            return $merged;
        }


        public function get_settings_array() {
            // Create array with all iet settings
            return array (
                array(
                    'option' => 'Alias',
                    'default' => false,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'all'
                ),
                array(
                    'option' => 'HeaderDigest',
                    'default' => 'None',
                    'type' => 'select',
                    'othervalue1' => 'CRC32C',
                    'state' => 'enabled'
                ),
                array(
                    'option' => 'DataDigest',
                    'default' => 'None',
                    'type' => 'select',
                    'othervalue1' => 'CRC32C',
                    'state' => 'enabled'
                ),
                array(
                    'option' => 'MaxConnections',
                    'default' => 1,
                    'type' => 'input',
                    'state' => 'disabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'MaxSessions',
                    'default' => 0,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'InitialR2T',
                    'default' => 'Yes',
                    'type' => 'select',
                    'othervalue1' => 'No',
                    'state' => 'enabled'
                ),
                array(
                    'option' => 'ImmediateData',
                    'default' => 'No',
                    'type' => 'select',
                    'othervalue1' => 'Yes',
                    'state' => 'enabled'
                ),
                array(
                    'option' => 'MaxRecvDataSegmentLength',
                    'default' => 8192,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'MaxXmitDataSegmentLength',
                    'default' => 8192,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'MaxBurstLength',
                    'default' => 262144,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'FirstBurstLength',
                    'default' => 65536,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'DefaultTime2Wait',
                    'default' => false,
                    'type' => 'input',
                    'state' => 'disabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'DefaultTime2Retain',
                    'default' => 0,
                    'type' => 'input',
                    'state' => 'disabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'MaxOutstandingR2T',
                    'default' => 1,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'DataPDUInOrder',
                    'default' => 'Yes',
                    'type' => 'select',
                    'othervalue1' => 'No',
                    'state' => 'disabled'
                ),
                array(
                    'option' => 'DataSequenceInOrder',
                    'default' => 'Yes',
                    'type' => 'select',
                    'othervalue1' => 'No',
                    'state' => 'disabled'
                ),
                array(
                    'option' => 'ErrorRecoveryLevel',
                    'default' => 0,
                    'type' => 'input',
                    'state' => 'disabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'NOPInterval',
                    'default' => false,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'NOPTimeout',
                    'default' => false,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'Wthreads',
                    'default' => 8,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                ),
                array(
                    'option' => 'QueuedCommands',
                    'default' => 32,
                    'type' => 'input',
                    'state' => 'enabled',
                    'chars' => 'digits'
                )
            );
        }
    }
?>