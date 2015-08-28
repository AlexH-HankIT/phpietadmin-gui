<div id='configure_target_body'>
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Add user</li>
            </ol>

            <div class="panel-body">
                <button id="adduserbutton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>

                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" id="addusertypeincomingcheckbox" name="type" value="IncomingUser" checked="checked"> Incoming
                    </label>
                    <label class="btn btn-default">
                        <input id="addusertypeoutgoingcheckbox" type="Radio" name="type" value="OutgoingUser"> Outgoing
                    </label>
                </div>
            </div>
            <br />
            <div class="table-responsive">
                <table id="addusertable" data-click-to-select="true" class="table table-striped searchabletable">
                    <thead>
                    <tr>
                        <th class="col-md-1"><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                        <th class="col-md-11">Username</th>
                    </tr>
                    </thead>
                        <tbody id="addusertablebody">
                        <?php foreach ($data as $row) { ?>
                            <tr>
                                <td hidden class="userid"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="col-md-1"><input class="addusercheckbox" type="checkbox"></td>
                                <td class="col-md-11"><?php echo htmlspecialchars($row['username']); ?></td>
                            </tr>
                        <?php } ?>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/target/adduser'], function (methods) {
                methods.add_event_handler_adduserbutton();
                methods.enable_filter_table_plugin();
            });
        });
    </script>
</div>