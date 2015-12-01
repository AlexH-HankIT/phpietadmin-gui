<div id="workspace">
	<div class="container">
		<div class='panel panel-default'>
			<ol class='panel-heading breadcrumb'>
				<li class="active">Releases</li>
			</ol>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-6">
						<div class='panel panel-success'>
							<div class="panel-heading">Installed version</div>
							<ul class="list-group">
								<li id="installedVersion" class="list-group-item"><?php echo $data['installedVersion'] ?></li>
								<li id="installedRelease" class="list-group-item"><?php echo $data['installedRelease'] ?></li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div id="newVersionPanel"></div>
					</div>
				</div>
			</div>
			<div class="panel-footer">
				<div class="row">
					<div class="col-md-12">
						<select title='Select release...' id="releaseChannelSelect">
							<option selected><?php echo ucfirst($data['release']) ?></option>
							<?php if ($data['release'] === 'stable') { ?>
								<option>Beta</option>
							<?php } else { ?>
								<option>Stable</option>
							<?php } ?>
						</select>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		require(['common'],function() {
			require(['pages/config/releases', 'domReady'],function(methods, domReady) {
				domReady(function () {
					methods.addEventHandler();
				});
			});
		});
	</script>
</div>