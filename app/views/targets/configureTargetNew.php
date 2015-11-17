<div id="workspace">
	<div class="container">
		<div id='configure_target_select'>
			<div class='panel panel-default'>
				<ol class='panel-heading breadcrumb'>
					<li><a href='#'>Targets</a></li>
					<li class='active'>Configure</a></li>
				</ol>
				<div class='panel-body'>
					<select id="targetSelect" class="form-control">
						<?php if ($data['iqn'] !== false) { ?>
							<option><?php echo $data['iqn'] ?></option>
						<?php } else { ?>
							<option id="default">Select a target to configure</option>
						<?php } ?>
						<?php foreach ($data['targets'] as $target) { ?>
							<option value="<?php echo htmlspecialchars($target['iqn']); ?>"><?php echo htmlspecialchars($target['iqn']); ?></option>
						<?php } ?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div id='configureTargetBodyWrapper'></div>
	<script>
		require(['common'],function() {
			require(['pages/target/configureTargetNew', 'domReady'],function(methods, domReady) {
				domReady(function () {
					methods.add_event_handler();
				});
			});
		});
	</script>
</div>