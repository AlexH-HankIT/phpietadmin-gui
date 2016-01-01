<div class="table-responsive">
    <table class="table table-striped">
        <thead>
        <tr>
            <?php foreach ($data['heading'] as $value) { ?>
                <th><?php echo htmlspecialchars($value); ?></th>
            <?php } ?>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($data['body'] as $value) { ?>
            <tr>
                <td class="sid"><?php echo htmlspecialchars($value['sid']) ?></td>
                <td><?php echo htmlspecialchars($value['initiator']) ?></td>
                <td><?php echo htmlspecialchars($value['cid']) ?></td>
                <td><?php echo htmlspecialchars($value['ip']) ?></td>
                <td><?php echo htmlspecialchars($value['state']) ?></td>
                <td><?php echo htmlspecialchars($value['hd']) ?></td>
                <td><?php echo htmlspecialchars($value['dd']) ?></td>
                <td>
                    <button class="btn btn-xs btn-danger deleteSessionButton">
                        <span class="glyphicon glyphicon-remove"></span> Disconnect
                    </button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>
<script>
    require(['common'], function () {
        require(['pages/target/session', 'domReady'], function (methods, domReady) {
            domReady(function () {
                methods.deleteSessionButtonQtip();
                methods.deleteSessionButton();
            });
        });
    });
</script>