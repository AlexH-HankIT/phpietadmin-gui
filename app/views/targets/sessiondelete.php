<div id="workspace">
    <div class="container">
        <div class="panel panel-default">
            <ol class='panel-heading breadcrumb'>
                <li><a href='#'>Targets</a></li>
                <li><a href='#'>Configure</a></li>
                <li class='active'>Delete session</li>
            </ol>
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
                            <td><a class="sessiondeletebutton" href="#"><span class="glyphicon glyphicon glyphicon-trash glyphicon-20"></span></a></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        require(['common'], function () {
            require(['pages/target/deletesession'], function (methods) {
                methods.add_qtip_sessiondeletebutton();
                methods.add_event_handler_sessiondeletebutton();
            });
        });
    </script>
</div>