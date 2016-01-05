<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <th>Option</th>
            <th>Value</th>
            <th class="text-center">Save</th>
            <th class="text-center">Info</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data as $row) { ?>
            <tr id="<?php echo htmlspecialchars($row['option']); ?>">
                <td hidden><input class="settingstablecheckbox" type="checkbox"></td>
                <?php if ($row['type'] == 'input') { ?>
                    <td class="option"><?php echo htmlspecialchars($row['option']); ?></td>
                    <td class="form-group">
                        <input class="value form-control <?php if ($row['defaultvalue'] !== 'false') echo 'required' ?>"
                            <?php if ($row['state'] == 0) echo 'disabled' ?>
                               value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>">
                    </td>
                    <td hidden><input class="default_value_before_change" type="text"
                                      value="<?php if ($row['defaultvalue'] !== 'false') echo htmlspecialchars($row['defaultvalue']); ?>">
                    </td>
                <?php } else if ($row['type'] == 'select') { ?>
                    <td class="option"><?php echo htmlspecialchars($row['option']); ?></td>
                    <td>
                        <select class="optionselector form-control" <?php if ($row['state'] == 0) echo 'disabled' ?>>
                            <option><?php echo htmlspecialchars($row['defaultvalue']); ?></option>
                            <option><?php echo htmlspecialchars($row['othervalue1']); ?></option>
                        </select>
                    </td>
                    <td hidden>
                        <input class="default_value_before_change" type="text"
                               value="<?php echo htmlspecialchars($row['defaultvalue']); ?>">
                    </td>
                <?php } ?>
                <td class="text-center">
                    <button class="btn btn-success has-spinner saveValueButton" type="submit" data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Saving...'>
                        <span class="glyphicon glyphicon-save" aria-hidden="true"></span> Save
                    </button>
                </td>
                <td class="text-center">
                    <button class="btn btn-info" data-content=" " data-placement="right"><span class="glyphicon glyphicon-info-sign"></span> Info</button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    require(['common'], function () {
        require(['pages/target/settings', 'domReady'], function (settings, domReady) {
            domReady(function () {
                settings.info();
                settings.add_event_handler_settings_table_checkbox();
                settings.add_event_handler_save_value();
                settings.remove_error();
            });
        });
    });
</script>