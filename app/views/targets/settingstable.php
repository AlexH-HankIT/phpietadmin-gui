<div class="workspacedirect">
    <div class="container">
        <div class="row">
            <?php foreach ($data as $tables) { ?>
                <div class="col-md-6">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                                <th>Option</th>
                                <th>Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tables as $row) { ?>
                                <tr class="<?php echo htmlspecialchars($row['option']); ?>">
                                    <td><input disabled class="settingstablecheckbox" type="checkbox"></td>
                                    <?php if ($row['type'] == 'input') { ?>
                                        <td><?php echo htmlspecialchars($row['option']); ?></td>
                                        <td><input class='value' <?php if ($row['state'] == 0) echo 'disabled' ?> type="text" value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>"></td>
                                        <td><input hidden class="default_value_before_change" type="text" value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>"></td>
                                    <?php } else if ($row['type'] == 'select') { ?>
                                        <td><?php echo htmlspecialchars($row['option']); ?></td>
                                        <td>
                                            <select <?php if ($row['state'] == 0) echo 'disabled' ?>>
                                                <option><?php echo htmlspecialchars($row['defaultvalue']); ?></option>
                                                <option hidden class="default_value_before_change"><?php echo htmlspecialchars($row['defaultvalue']); ?></option>
                                                <option><?php echo htmlspecialchars($row['othervalue1']); ?></option>
                                            </select>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            <?php } ?>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/settings'],function(methods) {
                methods.add_event_handler_settingstablecheckbox();
                methods.add_event_handler_save_settings();
            });
            require(['pages/settingstableqtip'],function(methods) {
                methods.add_qtip();
            });
        });
    </script>
</div>