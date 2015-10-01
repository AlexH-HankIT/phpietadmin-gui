<div id="configure_lvm_body">
	<div class="container">
		<?php if ($data['lv'][0]['Attr'][5] === 'o') { ?>
			<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume is in use!</div>
		<?php } ?>

		<?php if ($data['lv'][0]['Attr'][0] === 'o') { ?>
			<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume has snapshots, which will be automatically deleted!</div>
		<?php } ?>

		<div class='panel panel-default'>
			<ol class='panel-heading breadcrumb'>
				<li><a href='#'>LVM</a></li>
				<li><a href='#'>Configure</a></li>
				<li class='active'>Delete</li>
			</ol>
			<div class="panel-body">
				<div class="row">
					<div class="col-md-12">
						<label><input type="checkbox" id="safety_checkbox"> I understand, that this will delete all data stored on this volume</label>
					</div>
				</div>
			</div>

			<div class='panel-footer'>
				<button id="delete_volume_button" class="btn btn-danger" type='submit'><span class="glyphicon glyphicon-trash"></span> Delete</button>
			</div>
		</div>
	</div>

	<script>
		require(['common'], function () {
			require(['pages/lvm/delete', 'domReady'], function (methods, domReady) {
                domReady(function () {
				    methods.remove();
			    });
			});
		});
	</script>
</div>