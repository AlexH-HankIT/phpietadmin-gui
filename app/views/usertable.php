<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Users</li>
    </ol>
</div>

<div class="container">
    <table id="addusertable" class="table table-striped searchabletable">
        <thead>
            <tr>
                <th>Type</th>
                <th>Username</th>
                <th>Password</th>
                <th>Generate</th>
                <th><a href="#" id="adduserrowbutton"><span style="font-size: 15px" class="glyphicon glyphicon-plus" aria-hidden="true"></span></a></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="addusertablebody">
        <?php if (is_array($data)) { ?>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td hidden class="id"><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['type']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><?php echo htmlspecialchars($row['password']); ?></td>
                    <td></td>
                    <td><a href="#" class="deleteuserrow"><span style="font-size: 20px" class="glyphicon glyphicon-trash" aria-hidden="true"></span></a></td>
                    <td></td>
                </tr>
            <?php } ?>
        <?php } ?>
        </tbody>
    </table>
</div>

<script>
    require(['common'],function() {
        require(['pages/usertable']);
    });
</script>