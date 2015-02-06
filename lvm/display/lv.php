<?php
    require '../../views/header.html';
    require '../../views/nav.html';
    require '../../views/lvm/display/lv/header.html';
    require '../../views/overview/menu.html';

try {
    // Get array with volume groups and count
    $data = get_volume_groups();

    if ($data == "error") {
        throw new Exception("Error - Could not list volume groups");
    }

    require '../../views/lvm/display/lv/input.html';

    if (isset($_POST['vg_post'])) {
        if ($_POST['vg_post'] > 0) {
            $a = $_POST['vg_post'] - 1;
            $lvs2 = get_logical_volumes($data[$a]);

            if ($lvs2 == "error") {
                throw new Exception("Error - Volume group $data[$a] is empty");
            } else {
                require '../../views/lvm/display/lv/output.html';
            }
        }
    }
} catch (Exception $e) {
    $error = $e->getMessage();
    require '../../views/error.html';
}

    require '../../views/div.html';
    require '../../views/footer.html';
?>