<?php
    require 'views/header.html';
    require 'views/nav.html';
    require 'views/disks/header.html';
    require 'views/overview/menu.html';

    $lsblk_out = shell_exec("$sudo $lsblk -rn");
    $blk = explode ("\n", $lsblk_out);

    $blk = array_filter($blk, 'strlen');
    $count = count($blk);

    for ($i=0; $i < $count; $i++) {
        // Don't display lvm volumes
        if (strpos($blk[$i],"dm") === false) {
            $blk2[$i] = explode (" ", $blk[$i]);
        }

    }

    // Rebuild array index
    $blk2 = array_values($blk2);

    require 'views/disks/output.html';
    require 'views/div.html';
    require 'views/footer.html';
?>