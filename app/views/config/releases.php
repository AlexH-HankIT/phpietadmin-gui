<div id="workspace">
	<div class="container">
		<div class='panel panel-default'>
			<ol class='panel-heading breadcrumb'>
				<li class="active">Releases</li>
			</ol>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<select class="form-control">
								<option>Stable</option>
								<option>Beta</option>
							</select>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class='panel panel-success'>
							<div class="panel-heading">Installed version</div>
							<ul class="list-group">
								<li id="currentVersion" class="list-group-item">v0.6.1</li>
								<li id="currentRelease" class="list-group-item">stable</li>
							</ul>
						</div>
					</div>
					<div class="col-md-6">
						<div class='panel panel-warning'>
							<div class="panel-heading">Available version</div>
							<ul class="list-group">
								<li id="versionNew" class="list-group-item"></li>
								<li id="downloadNew" class="list-group-item"></li>
								<li id="docNew" class="list-group-item"></li>
								<li id="releaseNew" class="list-group-item"></li>
							</ul>
						</div>
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