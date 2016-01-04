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
                    <button class="btn btn-danger btn-xs deleteSessionButton has-spinner" type="submit"
                            data-loading-text='<span class="glyphicon glyphicon-refresh glyphicon-spin"></span> Disconnecting...'
                            data-container="body" data-toggle="popover" data-placement="right" data-trigger="hover"
                            data-content="To disconnect an initiator permanently you have to delete the acl allowing the connection."
                        >
                        <span class="glyphicon glyphicon-trash"></span> Disconnect
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
                methods.deleteSessionButton();
            });
        });
    });
</script>