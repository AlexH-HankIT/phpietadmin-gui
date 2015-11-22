<div class="container">
	<div id='delete_user_panel' class="panel panel-default">
		<ol class='panel-heading breadcrumb'>
			<li><a href='#'>Targets</a></li>
			<li><a href='#'>Configure</a></li>
			<li class='active'>Delete user</li>
		</ol>

		<div class="panel-body">
			<button id="deleteuserbutton" class="btn btn-danger" type="submit"><span
					class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
			</button>
		</div>
		<div class="table-responsive">
			<table class="table table-striped searchabletable" id="deleteusertable">
				<thead>
				<tr>
					<th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
					<th>Type</th>
					<th>User</th>
				</tr>
				</thead>
				<tbody id='delete_user_table_body'>
				<?php foreach ($data as $row) { ?>
					<tr>
						<td><input class="userDeleteCheckbox" type="checkbox"></td>
						<td class="type"><?php echo htmlspecialchars($row[0]); ?></td>
						<td><?php echo htmlspecialchars($row[1]); ?></td>
						<td hidden class="id"><?php echo htmlspecialchars($row[2]); ?></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	require(['common'], function () {
		require(['pages/target/deleteUser'], function (methods) {
			methods.add_event_handler_deleteuserbutton();
			methods.enable_filter_table_plugin();
		});
	});
</script>