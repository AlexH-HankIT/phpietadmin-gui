<?php
    class Disks {
        public function getDisks() {
            $a_config = parse_ini_file("/home/vm/ownCloud/Work/PhpstormProjects/mvctest/app/config.ini.php", true);

            $lsblk_out = shell_exec("{$a_config['misc']['sudo']} {$a_config['misc']['lsblk']} -rn");
            $blk = explode ("\n", $lsblk_out);

            $blk = array_filter($blk, 'strlen');
            $count = count($blk);

            for ($i=0; $i < $count; $i++) {
                // Don't display lvm volumes
                if (strpos($blk[$i],"dm") === false) {
                    $blk2[$i] = explode (" ", $blk[$i]);
                }

            }

            $table = array(
                0 => "Name",
                1 => "MAJ:MIN",
                2 => "RM",
                3 => "Size",
                4 => "RO",
                5 => "Type",
                6 => "Mountpoint"
            );

            $data[0] = $table;
            $data[1] = $blk2;

            return array_values($data);
        }
    }
?>