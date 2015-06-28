<div class = "container">
    <div class="row">
        <div class="col-md-10">
            <table id="addusertable" class="table table-striped searchabletable">
                <thead>
                <tr>
                    <th class="col-md-1"><span class="glyphicon glyphicon glyphicon-ok green glyphicon-20"></span></th>
                    <th class="col-md-11">Username</th>
                </tr>
                </thead>
                <?php if (is_array($data['user'])) { ?>
                    <tbody id="addusertablebody">
                        <?php foreach ($data['user'] as $row) { ?>
                            <tr>
                                <td hidden class="userid"><?php echo htmlspecialchars($row['id']); ?></td>
                                <td class="col-md-1"><input class="addusercheckbox" type="checkbox"/></td>
                                <td class="col-md-11"><?php echo htmlspecialchars($row['username']); ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                <?php } ?>
            </table>
        </div>
        <div class="col-md-2">
            <button id="adduserbutton" class="btn btn-success"><span class="glyphicon glyphicon-plus"></span> Add</button>

            <div class="btn-group top-buffer" data-toggle="buttons">
                <label class="btn btn-default active">
                    <input type="Radio" id="addusertypeincomingcheckbox" name="type" value="Incoming" checked="checked" /> Incoming
                </label>
                <label class="btn btn-default">
                    <input id="addusertypeoutgoingcheckbox" type="Radio" name="type" value="Outgoing" /> Outgoing
                </label>
            </div>
        </div>
    </div>
</div>

<script>
    $('.searchabletable').filterTable({minRows:0});
    require(['common'],function() {
        require(['pages/adduser']);
    });
</script>