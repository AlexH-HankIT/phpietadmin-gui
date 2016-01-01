<?php if (isset($data['session'])) { ?>
    <div class="alert alert-warning" role="alert">
        <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span>
        Warning - Target has sessions!</h3></div>
<?php } ?>
<div class="row">
    <div class="col-md-12">
        <select id="deleteLunSelect" data-live-search="true" data-width="100%" data-size="10">
            <?php foreach ($data['lun'] as $value) { ?>
                <option class="lun"
                        data-subtext="ID: <?php echo htmlspecialchars($value['id']); ?>"><?php echo htmlspecialchars($value['path']); ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div class="row top-buffer">
    <div class="col-md-12">
        <button id="deleteLunButton" class="btn btn-danger" type="submit">
            <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
        </button>
    </div>
</div>
<script>
    require(['common'], function () {
        require(['pages/target/deleteLun', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.add_event_handler_deletelunbutton();
            });
        });
    });
</script>