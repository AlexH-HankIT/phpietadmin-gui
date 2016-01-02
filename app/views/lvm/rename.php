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
    <div class="col-md-12">
        <input type="text" id="name_input" class="form-control" placeholder="New name..." required>
    </div>
</div>

<button id="rename_volume_button" class="btn btn-success top-buffer" type='submit' data-loading-text="Renaming..."><span class="glyphicon glyphicon-tag"></span>
    Rename
</button>
<script>
    require(['common'], function () {
        require(['pages/lvm/rename', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.rename();
                methods.focusInput();
            });
        });
    });
</script>
