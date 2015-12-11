<?php
namespace app\models\lvm\lv;

use app\models\lvm\logic\Parser;

class Logic extends Parser {
    protected function check() {
        if (empty($this->vg_name) || $this->lv_name === false || empty($this->lv_name)) {
            echo 'To use this function, please instantiate the object with an volume group and logical volume!';
            die();
        }
    }

    protected function check_vg_enough_space($size) {
        // check if $this->vg_name has enough space left...
        $data = $this->vg->get_vg();

        $result = $data[0]['VFree'] - $size;

        if ($result < 0) {
            return false;
        } else {
            return true;
        }
    }

    // this function returns all logical volume of $this->vg_name
    // even if the object was instantiated with a specific logical volume
    protected function get_all_volumes_from_this_vg() {
        // save $this->lv_name
        $temp = $this->lv_name;

        // set it to false
        $this->lv_name = false;

        $data = $this->parse_lvm('lv');

        $this->lv_name = $temp;

        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

    protected function return_snapshots() {
        // return snapshots of $this->lv_name
        if ($this->is_origin() === true) {
            $data = $this->get_all_volumes_from_this_vg();

            if ($data === false) {
                return false;
            } else {
                // create array with index and origin
                foreach ($data as $key => $lv) {
                    if (!empty($lv['Origin'])) {
                        $snapshots[$key] = $lv['Origin'];
                    }
                }

                if (!empty($snapshots)) {
                    foreach ($snapshots as $key => $snapshot) {
                        if ($snapshot == $this->lv_name) {
                            $snaps[$key] = $key;
                        }
                    }

                    if (!empty($snaps)) {
                        $snaps = array_values($snaps);

                        foreach ($snaps as $key => $snap) {
                            $return[$key] = $data[$snap];
                        }

                        if (!empty($return)) {
                            return $return;
                        } else {
                            return false;
                        }
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    protected function is_snapshot() {
        $data = $this->get_volume_type();

        if ($data == 's') {
            return true;
        } else {
            return false;
        }
    }

    protected function is_origin() {
        $data = $this->get_volume_type();

        if ($data == 'o') {
            return true;
        } else {
            return false;
        }
    }

    protected function get_origin() {
        if ($this->is_snapshot() === true) {
            $data = $this->parse_lvm('lv');

            if ($data !== false && !empty($data[0]['Origin'])) {
                return $data[0]['Origin'];
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    protected function get_volume_type() {
        $data = $this->parse_lvm('lv');

        if ($data !== false && !empty($data[0]['Attr'])) {
            /* data['Attr'][0] contains the volume type
               possible options:
               (m)irrored, (M)irrored without initial sync, (o)rigin
               (O)rigin with merging snapshot, (r)aid, (R)aid without initial  sync,  (s)napshot
               merging  (S)napshot,  (p)vmove, (v)irtual, mirror or raid (i)mage,
               mirror or raid (I)mage out-of-sync, mirror (l)og device, under (c)onversion
               thin (V)olume, (t)hin pool, (T)hin pool data, raid or thin pool m(e)tadata
               see man lvs(8)
            */
            return $data[0]['Attr'][0];
        } else {
            return false;
        }
    }
}