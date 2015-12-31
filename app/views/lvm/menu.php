<div class="workspace">
	<div class="container">
		<div id='configure_lvm_select'>
			<div class='panel panel-default'>
				<ol class='panel-heading breadcrumb'>
					<li><a href='#'>LVM</a></li>
					<li class='active'>Configure</li>
				</ol>
				<div class='panel-body'>
					<select id="logical_volume_selector" class="form-control">
						<option id="default">Select a volume to configure</option>
						<?php foreach ($data as $lv) { ?>
							<option data-vg="<?php echo htmlspecialchars($lv['VG']) ?>" data-lv="<?php echo htmlspecialchars($lv['LV']) ?>"><?php echo htmlspecialchars($lv['VG']) . ' | ' . htmlspecialchars($lv['LV']); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
		<div id='configure_lvm_menu'>
			<ul class="nav nav-tabs">
				<li class="active" id="configure_lvm_extent"><a class="configure_lvm_body_tab" href="/phpietadmin/lvm/configure/extent"><span class="glyphicon glyphicon-chevron-up"></span> Extent</a></li>
				<li><a class="configure_lvm_body_tab" href="/phpietadmin/lvm/configure/shrink"><span class="glyphicon glyphicon-chevron-down"></span> Shrink</a></li>
                <li class = "dropdown">
                    <a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-facetime-video"></span> Snapshot <b class = "caret"></b></a>
                    <ul class = "dropdown-menu">
                        <li><a class="configure_lvm_body_tab" href="/phpietadmin/lvm/configure/snapshot/add"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
                        <li><a class="configure_lvm_body_tab" href="/phpietadmin/lvm/configure/snapshot/delete"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
                    </ul>
                </li>
				<li><a class="configure_lvm_body_tab" href="/phpietadmin/lvm/configure/rename"><span class="glyphicon glyphicon-tag"></span> Rename</a></li>
				<li><a class="configure_lvm_body_tab" href="/phpietadmin/lvm/configure/delete"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
			</ul>
			<br />
		</div>
	</div>
	<div id='configure_lvm_body_wrapper'></div>
    <script>
        require(['common'],function() {
            require(['pages/lvm/configure_lvm'],function(methods) {
                methods.add_event_handler();
            });
        });
    </script>
</div>