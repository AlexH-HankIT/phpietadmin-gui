<div class="workspacedirect">
    <div class = "container">
        <ol class="breadcrumb">
            <li class="active">Add discovery user</li>
        </ol>

        <div class = "jumbotron">
            <div class="row">
                <div class="col-md-3">
                    <div class="btn-group" data-toggle="buttons">
                        <label class="btn btn-default active">
                            <input type="Radio" id="adddisusertypeincomingcheckbox" name="type" value="Incoming" checked="checked" /> Incoming
                        </label>
                        <label class="btn btn-default">
                            <input id="adddisusertypeoutgoingcheckbox" type="Radio" name="type" value="Outgoing" /> Outgoing
                        </label>
                    </div>
                </div>

                <div class="col-md-2 col-md-offset-7">
                    <button id="adddisuserbutton" type="button" class="btn btn-primary">Add</button>
                </div>
            </div>
        </div>
    </div>

    <div class = "container">
        <ol class="breadcrumb">
            <li class="active">Users</li>
        </ol>
        <table id="adddisusertable" class="table table-striped searchabletable">
            <thead>
            <tr>
                <th class="col-md-1"><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                <th class="col-md-11">Username</th>
            </tr>
            </thead>
            <?php if (is_array($data['user'])) { ?>
                <tbody id="adddisusertablebody">
                <?php foreach ($data['user'] as $row) { ?>
                    <tr>
                        <td hidden class="userid"><?php echo htmlspecialchars($row['id']); ?></td>
                        <td class="col-md-1"><input class="adddisusercheckbox" type="checkbox"/></td>
                        <td class="col-md-11"><?php echo htmlspecialchars($row['username']); ?></td>
                    </tr>
                <?php } ?>
                </tbody>
            <?php } ?>
        </table>
    </div>

    <script>
        require(['common'],function() {
            require(['pages/adddisuser'],function(methods) {
                methods.add_event_handler_adddisuserbutton();
            });
        });
    </script>
</div>