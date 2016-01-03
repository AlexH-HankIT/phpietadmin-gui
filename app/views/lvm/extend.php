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
        <input id="extend_size" type="text" value="<?php echo htmlspecialchars(intval($data['lv'][0]['LSize'])) ?>"
               class="form-control">
    </div>
    <div class="col-md-6 col-sm-6">
        <div data-max="<?php echo htmlspecialchars($data['lv'][0]['LSize'] + $data['vg'][0]['VFree'] - 1) ?>"
             data-min="<?php echo htmlspecialchars($data['lv'][0]['LSize']) ?>"
             data-value="<?php echo htmlspecialchars($data['lv'][0]['LSize']) ?>" id="extend_slider" class="slider">
        </div>
    </div>
</div>
<div class="row top-buffer">
    <div class="col-md-12">
        <label><input type="checkbox" id="remapLun"> Remap lun</label> <span id="remapLunExplanation" class="label label-info"><span class="glyphicon glyphicon-info-sign"></span> Info</span>
    </div>
</div>
<button id='extend_lv_button' class='btn btn-success top-buffer' data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Extending...'><span class='glyphicon glyphicon-plus'></span> Extend</button>
<script>
    require(['common'], function () {
        require(['pages/lvm/extend', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.extend();
            });
        });
    });
</script>