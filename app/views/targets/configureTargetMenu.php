<div class="container">
	<div id='configure_target_menu'>
		<ul class="nav nav-tabs">
			<li class="dropdown active">
				<a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-hdd"> LUN <b class = "caret"></b></a>
				<ul class="dropdown-menu">
					<li class="configureTargetBodyMenu active"><a href="/phpietadmin/targets/configure/<?php echo $data ?>#maplun"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
					<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/<?php echo $data ?>#deletelun"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
				</ul>
			</li>
			<li class = "dropdown">
				<a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-exclamation-sign"></span> ACL <b class = "caret"></b></a>
				<ul class = "dropdown-menu">
					<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/<?php echo $data ?>#addrule"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
					<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/deleterule/control"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
				</ul>
			</li>
			<li class = "dropdown">
				<a href='#' class = "dropdown-toggle" data-toggle = "dropdown"><span class="glyphicon glyphicon-user"></span> Users <b class = "caret"></b></a>
				<ul class = "dropdown-menu">
					<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/adduser"><span class="glyphicon glyphicon-plus"></span> Add</a></li>
					<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/deleteuser"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
				</ul>
			</li>
			<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/deletesession"><span class="glyphicon glyphicon-list"></span> Sessions</a></li>
			<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/settings"><span class="glyphicon glyphicon-cog"></span> Settings</a></li>
			<li class="configureTargetBodyMenu"><a href="/phpietadmin/targets/configure/deletetarget"><span class="glyphicon glyphicon-trash"></span> Delete</a></li>
		</ul>
		<br />
	</div>
</div>