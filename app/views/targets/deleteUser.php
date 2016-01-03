<button id="deleteUserButton" class="btn btn-danger has-spinner" type="submit" data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Deleting...'>
    <span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
</button>
<div class="table-responsive top-buffer">
    <table class="table table-striped searchabletable" id="deleteusertable">
        <thead>
        <tr>
            <th><input id="masterCheckbox" type="checkbox"></th>
            <th>Type</th>
            <th>User</th>
        </tr>
        </thead>
        <tbody id='delete_user_table_body'>
        <?php foreach ($data as $row) { ?>
            <tr>
                <td><input class="userDeleteCheckbox" type="checkbox"></td>
                <td class="type"><?php echo htmlspecialchars($row[0]); ?></td>
                <td><?php echo htmlspecialchars($row[1]); ?></td>
                <td hidden class="id"><?php echo htmlspecialchars($row[2]); ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    require(['common'], function () {
        require(['pages/target/deleteUser'], function (methods) {
            methods.add_event_handler_deleteuserbutton();
            methods.enable_filter_table_plugin();
        });
    });
</script>