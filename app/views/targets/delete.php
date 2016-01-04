<?php if (isset($data['session'])) { ?>
    <div class="alert alert-warning" role="alert">
        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Warning - Target has sessions!</h3>
    </div>
<?php } ?>
<?php if (isset($data['lun'])) { ?>
    <div class="alert alert-warning" role="alert">
        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Warning - Target has luns. Delete them before you delete the target!</h3></div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <label>
            <input id="deleteacl" type="checkbox" checked <?php if (isset($data['session'])) echo 'disabled' ?>>
            Delete acl
        </label> from initiator allow and target allow
    </div>
</div>
<div class="row top-buffer">
    <div class="col-md-12">
        <label>
            <input id="force" type="checkbox" <?php if (isset($data['session'])) echo 'checked disabled' ?>>
            Force
        </label>
        <button class="btn btn-xs btn-info" data-placement="right"
                data-toggle="popover"
                data-trigger="hover"
                data-content="Delete target even if in use. Requires 'Delete acl' 'Does not work, if a 'ALL' initiator acl is configured for this or all targets!">
            <span class="glyphicon glyphicon-info-sign" aria-hidden="true"></span> Info
        </button>
    </div>
</div>
<div class="row top-buffer">
    <div class="col-md-12">
        <button id="deleteTargetButton" class="btn btn-danger has-spinner" type="submit" data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Deleting...'>
            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
        </button>
    </div>
</div>
<script>
    require(['common'], function () {
        require(['pages/target/delete', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.add_event_handler_deletetargetbutton();
            });
        });
    });
</script>