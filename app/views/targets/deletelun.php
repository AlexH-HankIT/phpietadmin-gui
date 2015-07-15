<div class="workspacedirect">
    <div class="container">
        <div class='panel panel-default'>
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Delete lun</li>
            </ol>
            <div class='panel-body'>
                <div class="row">
                    <div class="col-md-12">
                        <select id="deletelunlunselection" class="form-control">
                            <?php foreach ($data as $value) { ?>
                                <option class="lun" name="<?php echo htmlspecialchars($value['path']); ?>" value="<?php echo htmlspecialchars($value['lun']) ?>"><?php echo htmlspecialchars($value['lun']) . ' ' . htmlspecialchars($value['path']); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class='panel-footer'>
                <div class="row">
                    <div class="col-md-12">
                        <button id="deletelunbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/deletelun'], function (methods) {
                methods.add_event_handler_deletelunbutton();
            });
        });
    </script>
</div>