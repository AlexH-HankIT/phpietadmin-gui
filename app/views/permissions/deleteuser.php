<div class="workspacedirect">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Delete user</li>
            </ol>

            <div class="panel-body">
                <button id="deleteuserbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-striped searchabletable" id="deleteusertable">
                    <thead>
                    <tr>
                        <th><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                        <th>Type</th>
                        <th>User</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($data as $row) { ?>
                        <tr>
                            <td><input class="userdeletecheckbox" type="checkbox"></td>
                            <td class="type"><?php echo htmlspecialchars($row[0]); ?></td>
                            <td class="user"><?php echo htmlspecialchars($row[1]); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>

        </div>


    </div>

    <script>
        require(['common'], function () {
            require(['pages/deleteuser'], function (methods) {
                methods.add_event_handler_targetselection();
                methods.add_event_handler_deleteuserbutton();
                methods.enable_filter_table_plugin();
            });
        });
    </script>
</div>