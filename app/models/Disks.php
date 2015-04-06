<?php
    class Disks {
        public function getDisks() {
            require 'Database.php';
            $database = new Database();
            $lsblk_out = shell_exec($database->getConfig('sudo') . " " . $database->getConfig('lsblk') . " -rn");
            $database->close();

            if (empty($lsblk_out)) {
                return 2;
            }

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