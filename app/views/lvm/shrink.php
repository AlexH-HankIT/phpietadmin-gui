<div class="alert alert-danger" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> You have to
    resize your filesystem before shrinking otherwise you will loose data!
</div>
<?php if ($data['lv'][0]['Attr'][5] === 'o') { ?>
    <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The
        selected logical volume is in use!
    </div>
<?php } ?>
<?php if ($data['lv'][0]['Attr'][0] === 'o') { ?>
    <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The
        selected logical volume has snapshots!
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-6 col-sm-6">
        <input id="shrink_input" type="text" class="form-control">
    </div>
    <div class="col-md-6 col-sm-6">
        <div data-max="<?php echo htmlspecialchars($data['lv'][0]['LSize']) ?>" id="shrink_slider" class="slider"></div>
    </div>
</div>
<button id="shrink_volume_button" class="btn btn-warning top-buffer" type='submit' data-loading-text="Shrinking..."><span class="glyphicon glyphicon-minus"></span> Shrink
</button>
<script>
    require(['common'], function () {
        require(['pages/lvm/shrink', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.shrink();
            });
        });
    });
</script>