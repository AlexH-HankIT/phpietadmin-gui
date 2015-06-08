<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Add user</li>
    </ol>
</div>

<div class = "container">
    <div class = "jumbotron">
        <div class="row">
            <div class="col-md-6">
                <select name="target" id="targetselection" class="form-control">
                    <option id="default">Select target...</option>
                    <?php foreach ($data['targets'] as $value) { ?>
                        <option value="<?php echo htmlspecialchars($value); ?>"> <?php echo htmlspecialchars($value); ?> </option>
                    <?php } ?>
                </select>
            </div>

            <div class="col-md-4">
                <div class="btn-group" data-toggle="buttons">
                    <label class="btn btn-default active">
                        <input type="Radio" id="addusertypeincomingcheckbox" value="Incoming" checked="checked" /> Incoming
                    </label>
                    <label class="btn btn-default">
                        <input id="addusertypeoutgoingcheckbox" type="Radio" value="Outgoing" /> Outgoing
                    </label>
                </div>
            </div>

            <div class="col-md-1">
                <button id="adduserbutton" type="button" class="btn btn-primary">Add</button>
            </div>
        </div>
    </div>
</div>

<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Users</li>
    </ol>
    <table id="addusertable" class="table table-striped searchabletable">
        <thead>
        <tr>
            <th>Check</th>
            <th>Username</th>
        </tr>
        </thead>
        <?php if (is_array($data['user'])) { ?>
            <tbody id="addusertablebody">
                <?php foreach ($data['user'] as $row) { ?>
                    <tr>
                        <td hidden class="id"><?php echo htmlspecialchars($row['id']); ?></td>
                        <td><input class="addusercheckbox" type="checkbox"/></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        <?php } ?>
    </table>
</div>

<script>
    require(['common'],function() {
        require(['pages/adduser']);
    });
</script>