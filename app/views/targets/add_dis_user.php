<div class="workspace">
    <div class = "container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li class='active'>Add discovery user</li>
            </ol>

            <div class="panel-body">
                <button id="adddisuserbutton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" id="adddisusertypeincomingcheckbox" name="type" value="Incoming" checked="checked"> Incoming
                    </label>
                    <label class="btn btn-default">
                        <input id="adddisusertypeoutgoingcheckbox" type="Radio" name="type" value="Outgoing"> Outgoing
                    </label>
                </div>
            </div>

            <div class="table-responsive">
                <br>
                <table id="adddisusertable" class="table table-striped searchabletable">
                    <thead>
                    <tr>
                        <th class="col-md-1"><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                        <th class="col-md-11">Username</th>
                    </tr>
                    </thead>
                    <tbody id="adddisusertablebody">
                    <?php foreach ($data as $row) { ?>
                        <tr>
                            <td hidden class="userid"><?php echo htmlspecialchars($row['id']); ?></td>
                            <td class="col-md-1"><input class="adddisusercheckbox" type="checkbox"></td>
                            <td class="col-md-11"><?php echo htmlspecialchars($row['username']); ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/target/add_dis_user'],function(methods) {
                methods.add_event_handler_adddisuserbutton();
                methods.enable_filter_table_plugin();
            });
        });
    </script>
</div>