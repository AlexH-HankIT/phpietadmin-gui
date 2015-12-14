<div class="container">
    <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-exclamation-sign"></span> "Writeback" is ignored by ietd, if you choose "blockio"!</div>
    <div class='panel panel-default'>
        <ol class='panel-heading breadcrumb'>
            <li><a href='#'>Targets</a></li>
            <li><a href='#'>Configure</a></li>
            <li class='active'>Map lun</li>
        </ol>
        <div class='panel-body'>
            <div class="row">
                <div class="col-md-12">
                    <select name="path" id="logical_volume_selector" class="form-control">
                        <option id="default">Select logical volume</option>
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
        </div>
        <div class='panel-footer'>
            <div class='row'>
                <div class='col-md-12'>
                    <button id="map_lun_button" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span>
                        Add
                    </button>
                </div>
            </div>
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
</div>