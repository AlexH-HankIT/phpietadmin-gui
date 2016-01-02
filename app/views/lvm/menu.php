<div class="workspace">
    <div class="container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>LVM</a></li>
                <li class='active'><a href='#'>Configure</a></li>
            </ol>
            <div class='panel-body'>
                <select id="logical_volume_selector" data-show-subtext="true" data-live-search="true" data-width="100%" title="Select a volume to configure" data-size="10">
                    <?php foreach ($data as $lv) { ?>
                        <option data-subtext="<?php echo htmlspecialchars($lv['VG']) ?>"><?php echo htmlspecialchars($lv['LV']); ?></option>
                    <?php } ?>
                </select>
                <div class="top-buffer" id='configure_lvm_menu' hidden>
                    <ul class="nav nav-tabs">
                        <li class="active" id="configure_lvm_extent"><a class="configure_lvm_body_tab" href="<?php echo WEB_PATH ?>/lvm/configure/extent"><span class="glyphicon glyphicon-chevron-up"></span> Extent</a></li>
                        <li><a class="configure_lvm_body_tab" href="<?php echo WEB_PATH ?>/lvm/configure/shrink"><span class="glyphicon glyphicon-chevron-down"></span> Shrink</a></li>
                        <li class="dropdown">
                            <a href='#' class="dropdown-toggle" data-toggle="dropdown"><span class="glyphicon glyphicon-facetime-video"></span> Snapshot <b class="caret"></b></a>
                            <ul class="dropdown-menu">
                                <li><a class="configure_lvm_body_tab" href="<?php echo WEB_PATH ?>/lvm/configure/snapshot/add"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                                <li><a class="configure_lvm_body_tab" href="<?php echo WEB_PATH ?>/lvm/configure/snapshot/delete"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                            </ul>
                        </li>
                        <li><a class="configure_lvm_body_tab" href="<?php echo WEB_PATH ?>/lvm/configure/rename"><span class="glyphicon glyphicon-tag"></span> Rename</a></li>
                        <li><a class="configure_lvm_body_tab" href="<?php echo WEB_PATH ?>/lvm/configure/delete"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                    </ul>
                </div>
                <div class="top-buffer" id="configureLvmBodyLoadingIndicator" hidden>
                    <div class="container text-center">
                        Loading, please wait...
                    </div>
                </div>
                <div class="top-buffer" id="configureLvmBody"></div>
            </div>
        </div>
    </div>
    <script>
        require(['common'], function () {
            require(['pages/lvm/menu'], function (methods) {
                methods.add_event_handler();
            });
        });
    </script>
</div>