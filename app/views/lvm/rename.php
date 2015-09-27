<div id="configure_lvm_body">
	<div class="container">
		<?php if ($data['lv'][0]['Attr'][5] === 'o') {?>
			<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume is in use!</div>
		<?php } ?>

		<?php if ($data['lv'][0]['Attr'][0] === 'o') {?>
			<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume has snapshots!</div>
		<?php } ?>

		<div class='panel panel-default'>
			<ol class='panel-heading breadcrumb'>
				<li><a href='#'>LVM</a></li>
				<li><a href='#'>Configure</a></li>
				<li class='active'>Rename</li>
			</ol>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<input type="text" id="name_input" class="form-control" placeholder="New name..." required>
					</div>
				</div>
			</div>

			<div class='panel-footer'>
				<button id="rename_volume_button" class="btn btn-success" type='submit'><span class="glyphicon glyphicon-tag"></span> Rename</button>
			</div>
		</div>
	</div>

	<script>
		require(['common'], function () {
			require(['pages/lvm/rename'], function (methods) {
				methods.rename();
			});
		});
	</script>
</div>