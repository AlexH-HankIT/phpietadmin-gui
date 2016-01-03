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
        <a href="#">
            <span id="icon_force" class="glyphicon glyphicon-question-sign glyphicon-20" aria-hidden="true"></span>
        </a>
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
                methods.add_qtip();
            });
        });
    });
</script>