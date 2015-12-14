<div class="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li class='active'>Delete discovery user</li>
            </ol>
            <div class="panel-body">
                <button id="delete_dis_user_button" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
                </button>
            </div>
            <div class="table-responsive">
                <br>
                <table class="table table-striped searchabletable" id="deletedisusertable">
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
                            <td><input class="delete_dis_user_checkbox" type="checkbox"></td>
                            <td class="delete_dis_user_type"><?php echo htmlspecialchars($row[0]) ?></td>
                            <td><?php echo htmlspecialchars($row[1]) ?></td>
                            <td hidden class="delete_dis_user_id"><?php echo $row[2] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <script>
        require(['common'],function() {
            require(['pages/target/delete_dis_user'],function(methods) {
                methods.add_event_handler_deletedisuserbutton();
                methods.enable_filter_table_plugin();
            });
        });
    </script>
</div>