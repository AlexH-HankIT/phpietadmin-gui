<div id='configure_target_body'>
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Settings</li>
            </ol>
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th>Save</th>
                        <th>Option</th>
                        <th>Value</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row) { ?>
                        <tr id="<?php echo htmlspecialchars($row['option']); ?>">
                            <td hidden><input class="settingstablecheckbox" type="checkbox"></td>
                            <td><a href="#" class="savevalueinput"><span class="glyphicon glyphicon-save glyphicon-20"></span></a></td>
                            <?php if ($row['type'] == 'input') { ?>
                                <td class="option"><?php echo htmlspecialchars($row['option']); ?></td>
                                <td><input class="value form-control <?php if ($row['defaultvalue'] !== 'false') echo 'required' ?>" <?php if ($row['state'] == 0) echo 'disabled' ?> value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>"></td>
                                <td hidden><input class="default_value_before_change" type="text" value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>"></td>
                            <?php } else if ($row['type'] == 'select') { ?>
                                <td class="option"><?php echo htmlspecialchars($row['option']); ?></td>
                                <td>
                                    <select class="optionselector form-control" <?php if ($row['state'] == 0) echo 'disabled' ?>>
                                        <option><?php echo htmlspecialchars($row['defaultvalue']); ?></option>
                                        <option><?php echo htmlspecialchars($row['othervalue1']); ?></option>
                                    </select>
                                </td>
                                <td hidden><input class="default_value_before_change" type="text" value="<?php echo htmlspecialchars($row['defaultvalue']); ?>"></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/target/settings', 'domReady', 'pages/target/settingstableqtip'], function (methods, domReady, methods2) {
                domReady(function () {
                    methods.add_event_handler_settings_table_checkbox();
                    methods.add_event_handler_save_value();
                    methods.remove_error();
                    methods2.add_qtip();
                });
            });
        });
    </script>
</div>