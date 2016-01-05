<?php
namespace app\models;

use app\models\logging,
    app\core,
    app\models;

class Disks extends core\BaseModel {
    public function get_disks($format = 'array') {
        $return = models\Misc::logExec($this->database->get_config('lsblk')['value'] . ' --pairs');

        if ($return['result'] == 0) {
            if (!empty($return['status'])) {
                foreach ($return['status'] as $key => $data) {
                    preg_match_all('/(\w+)\s*=\s*(["\'])((?:(?!\2).)*)\2/', $data, $temp, PREG_SET_ORDER);

                    foreach ($temp as $value) {
                        $disks[$key][$value[1]] = $value[3];
                    }
                }

                array_walk($disks, function (&$value) {
                    if ($value['TYPE'] == 'lvm') {
                        // unset doesn't work with references
                        // instead we set the value to NULL
                        // which will remove the variable and unset its value
                        $value = NULL;
                    }
                });

                $data = array(
                    'title' => 'Disks',
                    'heading' => array_keys($disks[0]),
                    'body' => array_values(array_filter($disks))
                );

                if ($format === 'json') {
                    return json_encode($data);
                } else {
                    return $data;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
}