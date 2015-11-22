<div id='configure_target_body'>
    <div class="container">
        <?php if (isset($data['session'])) { ?>
            <div class="alert alert-warning" role="alert"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Warning - Target has sessions!</h3></div>
        <?php } ?>

        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Delete lun</li>
            </ol>
            <div class='panel-body'>
                <div class="row">
                    <div class="col-md-12">
                        <select id="deleteLunSelect" class="form-control">
                            <?php foreach ($data['lun'] as $value) { ?>
                                <option class="lun" data-path="<?php echo htmlspecialchars($value['path']); ?>" value="<?php echo htmlspecialchars($value['id']) ?>"><?php echo htmlspecialchars($value['id']) . ' | ' . htmlspecialchars($value['path']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class='panel-footer'>
                <div class="row">
                    <div class="col-md-12">
                        <button id="deleteLunButton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
                    </div>
                </div>
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
    </div>
</div>