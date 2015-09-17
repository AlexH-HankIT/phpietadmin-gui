<div id="workspace">
    <div class="container">
            <div id='configure_target_select'>
                <div class='panel panel-default'>
                    <ol class='panel-heading breadcrumb'>
                        <li><a href='#'>Targets</a></li>
                        <li class='active'>Configure</a></li>
                    </ol>

                    <div class='panel-body'>
                        <select id="target_selector" class="form-control">
                            <option id="default">Select a target to configure</option>
                            <?php foreach ($data as $target) { ?>
                                <option value="<?php echo htmlspecialchars($target['iqn']); ?>"><?php echo htmlspecialchars($target['iqn']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div id='configure_target_menu'>
                <ul class="nav nav-tabs">
                    <li class = "dropdown active configure_target_map_lun">
                        <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"> LUN <b class = "caret"></b></a>
                        <ul class = "dropdown-menu">
                            <li class="active configure_target_map_lun"><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/maplun"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                            <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/deletelun"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                        </ul>
                    </li>
                    <li class = "dropdown">
                        <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-exclamation-sign"></span> ACL <b class = "caret"></b></a>
                        <ul class = "dropdown-menu">
                            <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/addrule"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                            <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/deleterule/control"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                        </ul>
                    </li>
                    <li class = "dropdown">
                        <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-user"></span> Users <b class = "caret"></b></a>
                        <ul class = "dropdown-menu">
                            <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/adduser"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                            <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/deleteuser"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                        </ul>
                    </li>
                    <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/deletesession"><span class="glyphicon glyphicon-list"></span> Sessions</a></li>
                    <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
                    <li><a class="configure_target_body_tab" href="/phpietadmin/targets/configure/deletetarget"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                </ul>
				<br />
			</div>
	</div>
	<div id='configure_target_body_wrapper'></div>
	<script>
		require(['common'],function() {
			require(['pages/target/configuretarget'],function(methods) {
				methods.add_event_handler();
			});
		});
	</script>
</div>