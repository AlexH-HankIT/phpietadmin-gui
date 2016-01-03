<?php if ($data['lv'][0]['Attr'][5] === 'o') { ?>
    <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume is in use!
    </div>
<?php } ?>

<?php if ($data['lv'][0]['Attr'][0] === 'o') { ?>
    <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> The selected logical volume has snapshots, which will be automatically deleted!
    </div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <label><input type="checkbox" id="safety_checkbox"> I understand, that this will delete all data stored on this volume</label>
    </div>
</div>
<button id="delete_volume_button" class="btn btn-danger top-buffer" type='submit' data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Deleting...'><span
        class="glyphicon glyphicon-trash"></span> Delete
</button>
<script>
    require(['common'], function () {
        require(['pages/lvm/delete', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.remove();
            });
        });
    });
</script>