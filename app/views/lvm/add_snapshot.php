<?php if ($data['lv'][0]['Attr'][5] === 'o') { ?>
    <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The
        selected logical volume is in use!
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <div class='input-group'>
            <span class='input-group-addon' id='basic-addon1'>Name: </span>
            <input id="add_snapshot_name_input" type="text" value="Logical volume name + timestamp" class="form-control"
                   disabled>
        </div>
    </div>
</div>
<div class="row top-buffer">
    <div class="col-md-6 col-sm-6">
        <input id="add_snapshot_size_input" type="text" class="form-control" required>
    </div>
    <div class="col-md-6 col-sm-6">
        <div data-max="<?php echo htmlspecialchars($data['vg'][0]['VFree']) ?>" id="add_snapshot_size_slider"
             class="slider"></div>
    </div>
</div>
<button id="create_snapshot" class="btn btn-success top-buffer" type='submit' data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Shooting...'>
    <span class="glyphicon glyphicon-camera"></span> Shoot it
</button>
<script>
    require(['common'], function () {
        require(['pages/lvm/add_snapshot', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.add_snapshot();
            });
        });
    });
</script>
