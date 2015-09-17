<div id="configure_target_body">
	<div class="container">
		<div class='panel panel-default'>
			<ol class='panel-heading breadcrumb'>
				<li class='active'>Objects</li>
			</ol>
			<div class="table-responsive">
				<table class='table'>
					<thead>
					<tr>
						<th><input id="object_delete_checkbox_all" type="checkbox"></th>
						<th>Type</th>
						<th>Name</th>
						<th>Value</th>
					</tr>
					</thead>
					<tbody>
					<?php foreach ($data as $row) { ?>
						<tr>
							<td><input class="object_delete_checkbox" type="checkbox"></td>
							<td><?php if (isset($row['display_name'])) echo htmlspecialchars($row['display_name']); else echo 'N/A' ?></td>
							<td><?php if (isset($row['name'])) echo htmlspecialchars($row['name']); else echo 'N/A' ?></td>
							<td class='object_value'><?php echo htmlspecialchars($row['value']) ?></td>
						</tr>
					<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>