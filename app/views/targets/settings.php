<div class="container">
	<div class="panel panel-default">
		<ol class='panel-heading breadcrumb'>
			<li><a href='#'>Targets</a></li>
			<li><a href='#'>Configure</a></li>
                <li class='active'>Settings</li>
		</ol>
		<div class="table-responsive">
			<table class="table table-striped">
				<thead>
				<tr>
					<th>Option</th>
					<th>Value</th>
					<th>Save</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($data as $row) { ?>
					<tr id="<?php echo htmlspecialchars($row['option']); ?>">
						<td hidden><input class="settingstablecheckbox" type="checkbox"></td>
						<?php if ($row['type'] == 'input') { ?>
							<td class="option"><?php echo htmlspecialchars($row['option']); ?></td>
							<td class="form-group"><input class="value form-control <?php if ($row['defaultvalue'] !== 'false') echo 'required' ?>" <?php if ($row['state'] == 0) echo 'disabled' ?> value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>"></td>
							<td hidden><input class="default_value_before_change" type="text" value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>"></td>
						<?php } else if ($row['type'] == 'select') { ?>
							<td class="option"><?php echo htmlspecialchars($row['option']); ?></td>
							<td>
								<select class="optionselector form-control" <?php if ($row['state'] == 0) echo 'disabled' ?>>
									<option><?php echo htmlspecialchars($row['defaultvalue']); ?></option>
									<option><?php echo htmlspecialchars($row['othervalue1']); ?></option>
								</select>
							</td>
							<td hidden><input class="default_value_before_change" type="text" value="<?php echo htmlspecialchars($row['defaultvalue']); ?>"></td>
						<?php } ?>
						<td><button class="btn btn-xs btn-success savevalueinput"><span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save</button></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script>
	require(['common'], function () {
		require(['pages/target/settings', 'domReady', 'pages/target/qtipSettings'], function (settings, domReady, qtipSettings) {
			domReady(function () {
				settings.add_event_handler_settings_table_checkbox();
				settings.add_event_handler_save_value();
				settings.remove_error();
				qtipSettings.add_qtip();
			});
		});
	});
</script>