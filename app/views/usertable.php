<div class = "container">
    <ol class="breadcrumb">
        <li class="active">Users</li>
    </ol>
</div>

<div class="container">
    <table id="addusertable" class="table table-striped searchabletable">
        <thead>
            <tr>
                <th>Username</th>
                <th>Password</th>
                <th><a href="#" id="adduserrowbutton"><span class="glyphicon glyphicon-plus glyphicon-20" aria-hidden="true"></span></a></th>
                <th></th>
            </tr>
        </thead>
        <tbody id="addusertablebody">
        <?php if (is_array($data)) { ?>
            <?php foreach ($data as $row) { ?>
                <tr>
                    <td hidden class="id"><?php echo htmlspecialchars($row['id']); ?></td>
                    <td><?php echo htmlspecialchars($row['username']); ?></td>
                    <td><span class="passwordfield"><span class="passwordfieldplaceholder"><i>Show</i></span><span class="password" hidden><?php echo htmlspecialchars($row['password']); ?></span></span></td>
                    <td><a href="#" class="deleteuserrow"><span class="glyphicon glyphicon-trash glyphicon-20" aria-hidden="true"></span></a></td>
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