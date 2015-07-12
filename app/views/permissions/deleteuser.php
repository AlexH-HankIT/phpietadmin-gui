<div class="workspacedirect">
    <div class = "container">
        <div class="row">
            <div class="col-md-11">
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
                                    <td><input class="userdeletecheckbox" type="checkbox"/></td>
                                    <td class="type"><?php echo htmlspecialchars($row[0]); ?></td>
                                    <td class="user"><?php echo htmlspecialchars($row[1]); ?></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="col-md-1">
                <button id="deleteuserbutton" class="btn btn-danger" type="submit"><span class="glyphicon glyphicon-warning-sign" aria-hidden="true"></span> Delete</button>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/deleteuser'],function(methods) {
                methods.add_event_handler_targetselection();
                methods.add_event_handler_deleteuserbutton();
            });
        });
    </script>
</div>