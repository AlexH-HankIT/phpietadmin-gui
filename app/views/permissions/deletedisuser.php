<div class="workspacedirect">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li class='active'>Delete discovery user</li>
            </ol>

            <div class="panel-body">
                <button id="deletedisuserbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete
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
                            <td><input class="deletedisusercheckbox" type="checkbox"/></td>
                            <td class="deletedisusertype"><?php echo $row[0] ?></td>
                            <td class="deletedisusername"><?php echo $row[1] ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>


    <script>
        require(['common'],function() {
            require(['pages/deletedisuser'],function(methods) {
                methods.add_event_handler_deletedisuserbutton();
                methods.enable_filter_table_plugin();
            });
        });
    </script>
</div>