<script>
    $('.searchabletable').filterTable({minRows:0});
</script>

<div class="container">
    <ol class="breadcrumb">
        <li class="active">Users</li>
    </ol>

    <div class = "row">
        <div class = "col-md-12">
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
</div>