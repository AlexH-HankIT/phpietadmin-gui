<div id="workspace">
	<div class="container">

		<div class="panel panel-default">
			<div class="panel-heading">Volume groups</div>
			<div class="panel-body top-buffer">
				<div class="row">
					<div class="col-md-6">
						<ul id="all" class="vg">
							<li hidden></li>
							<li class="ui-state-default list-group-item">Item 1</li>
							<li class="ui-state-default list-group-item">Item 2</li>
						</ul>
					</div>
					<div class="col-md-6">
						<ul id="used" class="vg">
							<li hidden></li>
							<li class="ui-state-default list-group-item">Item 3</li>
							<li class="ui-state-default list-group-item">Item 4</li>
							<li class="ui-state-default list-group-item">Item 5</li>
						</ul>
					</div>
				</div>
			</div>
		</div>


		<script>
			require(['common'], function () {
				require(['pages/config/vg'], function (methods) {
					methods.drag_drop();
				});
			});
		</script>
	</div>