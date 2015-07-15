<div class="workspacedirect">
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
                        <?php foreach ($data[1] as $value) { ?>
                            <th><?php echo htmlspecialchars($value); ?></th>
                        <?php } ?>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php for ($i = 1; $i < count($data[0]); $i++) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($data[0][0]['tid']); ?></td>
                            <td class="sid"><?php echo htmlspecialchars($data[0][$i]['sid']); ?></td>
                            <td><?php echo htmlspecialchars($data[0][$i]['initiator']); ?></td>
                            <td class="cid"><?php echo htmlspecialchars($data[0][$i]['cid']); ?></td>
                            <td class="ip"><?php echo htmlspecialchars($data[0][$i]['ip']); ?></td>
                            <td><?php echo htmlspecialchars($data[0][$i]['state']); ?></td>
                            <td><?php echo htmlspecialchars($data[0][$i]['hd']); ?></td>
                            <td><?php echo htmlspecialchars($data[0][$i]['dd']); ?></td>
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
            require(['pages/deletesession'], function (methods) {
                methods.add_qtip_sessiondeletebutton();
                methods.add_event_handler_sessiondeletebutton();
            });
        });
    </script>
</div>