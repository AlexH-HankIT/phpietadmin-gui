<?php
    require '../../includes.php';
    $layout->print_nav();
    $layout->print_title("Logical Volumes");
    require '../../views/overview/menu.html';

try {
    // Create lvm object
    $lvm = new Lvm;

    // Get array with volume groups and count
    $data = $lvm->get_volume_groups();

    if ($data == "error") {
        throw new Exception("Error - Could not list volume groups");
    }

    require '../../views/lvm/display/lv/input.html';

    if (isset($_POST['vg_post'])) {
        if ($_POST['vg_post'] > 0) {
            $a = $_POST['vg_post'] - 1;
            $lvs2 = $lvm->get_logical_volumes($data[$a]);

            if ($lvs2 == "error") {
                throw new Exception("Error - Volume group $data[$a] is empty");
            } else {
                require '../../views/lvm/display/lv/output.html';
            }
        }
    }
} catch (Exception $e) {
    $layout->print_error($e);
}

    $layout->print_footer();
?>