<div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> "Writeback" is
    ignored by ietd, if you choose "blockio"!
</div>
<div class="row">
    <div class="col-md-12">
        <select name="path" id="logical_volume_selector" data-live-search="true" data-width="100%" title="Select a logical volume..." data-size="10">
            <?php foreach ($data as $value) { ?>
                <option><?php echo htmlspecialchars($value) ?></option>
            <?php } ?>
        </select>
    </div>
</div>
<div class="row top-buffer">
    <div class="col-md-6 col-xs-6">
        <select name="type" id="type" multiple class="form-control">
            <option selected value="fileio">fileio</option>
            <option value="blockio">blockio</option>
        </select>
    </div>
    <div class="col-md-6 col-xs-6">
        <select name="mode" id="mode" multiple class="form-control">
            <option selected value="wt">Write through</option>
            <option value="wb">Write back</option>
            <option value="ro">Read only</option>
        </select>
    </div>
</div>
<div class='row top-buffer'>
    <div class='col-md-12'>
        <button id="map_lun_button" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>
            Add
        </button>
    </div>
</div>
<script>
    require(['common'], function () {
        require(['pages/target/maplun', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.add_event_handler_maplunbutton();
            });
        });
    });
</script>