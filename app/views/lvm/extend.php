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
				<li class='active'>Extent</li>
			</ol>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-6">
                        <input id="extend_size" type="text" value="<?php echo htmlspecialchars(intval($data['lv'][0]['LSize'])) ?>" class="form-control">
                    </div>
                    <div class="hidden-md hidden-lg">
                        <br>
                    </div>
                    <div class="col-md-6">
                        <div data-max="<?php echo htmlspecialchars($data['lv'][0]['LSize'] + $data['vg'][0]['VFree'] - 1) ?>" data-min="<?php echo htmlspecialchars($data['lv'][0]['LSize']) ?>" data-value="<?php echo htmlspecialchars($data['lv'][0]['LSize']) ?>" id="extend_slider" class="slider">
                        </div>
                    </div>
                </div>
				<div class="row top-buffer">
					<div class="col-md-12">
						<label><input type="checkbox" id="remapLun"> Remap lun</label> <a href="#"><span id="remapLunExplanation" class="glyphicon glyphicon-question-sign glyphicon-20" aria-hidden="true"></span></a>
					</div>
				</div>
            </div>
			<div class='panel-footer'>
				<div class='row'>
					<div class='col-md-12'>
						<button id='extend_lv_button' class='btn btn-success'><span class='glyphicon glyphicon-plus'></span> Extend
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<script>
		require(['common'], function () {
			require(['pages/lvm/extend', 'domReady'], function (methods, domReady) {
                domReady(function () {
				    methods.extend();
			    });
			});
		});
	</script>
</div>